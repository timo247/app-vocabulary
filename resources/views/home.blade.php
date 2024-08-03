<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Inter:ital,opsz,wght@0,14..32,100..900;1,14..32,100..900&display=swap"
        rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <title>Vocabulaire sérère</title>
    <style>
        html {
            font-family: "Inter", sans-serif;
        }

        @media (max-width: 600px) {

            th,
            td {
                font-size: 14px;
                padding: 4px;
            }

            .modal-content {
                width: 90%;
            }
        }

        table {
            width: 100%;
            border-collapse: collapse;
            table-layout: fixed;
        }

        th,
        td {
            border: 1px solid black;
            padding: 8px;
            text-align: left;
            position: relative;
        }

        .third-col {
            width: 8%
        }

        .third-header {
            border: none;
            background-color: white;
        }

        th {
            background-color: #f2f2f2;
            text-align: center;
        }

        button {
            padding: 5px 10px;
        }

        .add-button {
            margin-top: 10px;
            padding: 10px 20px;
        }

        .edit-button {
            border: none;
            background: none;
            cursor: pointer;
            margin-right: 5px;
        }

        .delete-button {
            padding: 5px 10px;
        }

        .cell-content {
            display: flex;
            align-items: center;
        }

        .cell-clickable-area {
            flex: 1;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
            justify-content: center;
            align-items: center;
        }

        .modal-content {
            background-color: #fefefe;
            margin: auto;
            padding: 20px;
            border: 1px solid #888;
            width: 300px;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .hidden {
            display: none;
        }

        .no-border {
            border: none;
        }
    </style>
</head>

<body>

    <h2>Phrases</h2>
    <table class="sentences">
        <thead>
            <tr>
                <th>Français</th>
                <th>Sérère</th>
                <th class="third-header third-col"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>
    <button class="add-button" onclick="addRow('sentences')">Ajouter une ligne</button>


    <h2>Mots</h2>
    <table class="words">
        <thead>
            <tr>
                <th>Français</th>
                <th>Sérère</th>
                <th class="third-header third-col"></th>
            </tr>
        </thead>
        <tbody>
        </tbody>
    </table>

    <button class="add-button" onclick="addRow('words')">Ajouter une ligne</button>

    <!-- Modale -->
    <div id="editCellModale" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Changer le texte:</p>
            <input type="text" id="modalInput">
            <input type="hidden" id="modalCellId">
            <input type="hidden" id="modalKnowledgeLevel">
            <input type="hidden" id="modalLanguage">
            <button onclick="saveChanges()">OK</button>
            <p id="modalError" style="color: red; display: none;">Erreur lors de la mise à jour. Veuillez réessayer.</p>
        </div>
    </div>

    <script>
        const apiUrl = 'http://127.0.0.1:8000/api'
        const knowledgeColors = [
            "#6E69FF", "#ef476f", "#ffd166", "#FFFA75", "#AEFCB2", "white"
        ]
        let currentCellSpan;

        const vocabularies = @json($vocabularies);
        console.log('vocabularies', vocabularies);

        function seedTables() {
            vocabularies.forEach(voc => {
                let tableType = "words"
                if (voc.is_sentence) {
                    tableType = "sentences"
                }
                addRow(tableType, voc.french, voc.serere, voc.correctly_translated, voc.correctly_understood, voc
                    .id)
            });
        }

        function addRow(tableClass, frenchValue, serereValue, correctlyTranslated, correctlyUnderstood, id) {
            const table = document.querySelector(`.${tableClass}`).getElementsByTagName('tbody')[0];
            if (frenchValue == null) {
                frenchValue = "En Français"
            }
            if (serereValue == null) {
                serereValue = "En Sérère"
            }

            const newRow = table.insertRow();

            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);

            cell1.innerHTML =
                `<div class="cell-content" data-id=${id} data-language="french"><button class="edit-button" onclick="editCell(this)">✏️</button><div class="cell-clickable-area" onclick="changeCellColor(this)" data-color=${correctlyTranslated}><span>${frenchValue}</span></div></div>`
            cell2.innerHTML =
                `<div class="cell-content" data-id=${id} data-language="serere"><button class="edit-button" onclick="editCell(this)">✏️</button><div class="cell-clickable-area" onclick="changeCellColor(this)" data-color=${correctlyUnderstood}></button><span>${serereValue}</span></div></div>`;
            cell3.innerHTML =
                '<button class="delete-button" onclick="deleteRow(this)" data-id=${id}>🗑️</button>';
            cell3.classList.add("no-border");

            cell1.style.backgroundColor = knowledgeColors[correctlyTranslated]
            if (correctlyTranslated < 5) {
                cell2.style.backgroundColor = knowledgeColors[5]
                cell2.querySelector('.cell-clickable-area').dataset
                    .color-- //allows to have a purple value on first click of the cell when the last translation number switched from 4 to 5
            } else {
                cell2.style.backgroundColor = knowledgeColors[correctlyUnderstood]
            }
        }

        function deleteRow(button) {
            const row = button.parentNode.parentNode;
            row.parentNode.removeChild(row);
        }

        function editCell(button) {
            const currentCell = button.parentNode;

            currentCellSpan = button.parentNode.querySelector('span');
            const vocId = currentCell.getAttribute('data-id');
            const cellClickableArea = currentCell.querySelector('.cell-clickable-area')
            const knowledgeLevel = cellClickableArea.getAttribute('data-color');
            const cellLanguage = currentCell.getAttribute('data-language');


            document.getElementById('modalInput').value = currentCellSpan.textContent;
            document.getElementById('modalCellId').value = vocId;
            document.getElementById('modalKnowledgeLevel').value = knowledgeLevel;
            document.getElementById('modalLanguage').value = cellLanguage;
            document.getElementById('editCellModale').style.display = "flex";
            document.getElementById('modalInput').focus()
            document.getElementById('modalInput').select()
        }

        function changeCellColor(cell) {
            const language = cell.parentNode.dataset.language
            const id = cell.parentNode.dataset.id

            if (cell.dataset.color < 5 && cell.dataset.color >= 0) {
                cell.dataset.color++;
            } else if (cell.dataset.color > 5) {
                cell.dataset.color = 5;
            } else {
                cell.dataset.color = 0;
            }

            if (language == 'french') {
                fetch(`${apiUrl}/update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // if Laravel CSRF protection
                        },
                        body: JSON.stringify({
                            id: id,
                            french: cell.querySelector('span').textContent,
                            correctly_translated: cell.dataset.color
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            cell.parentNode.parentNode.style.backgroundColor = `${knowledgeColors[cell.dataset.color]}`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            } else {
                console.log('on serere', cell.dataset.color)
                fetch(`${apiUrl}/update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // if Laravel CSRF protection
                        },
                        body: JSON.stringify({
                            id: id,
                            serere: cell.querySelector('span').textContent,
                            correctly_understood: cell.dataset.color
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            cell.parentNode.parentNode.style.backgroundColor = `${knowledgeColors[cell.dataset.color]}`;
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                    });
            }
        }

        function closeModal() {
            document.getElementById('editCellModale').style.display = "none";
        }

        function saveChanges() {
            const cellId = document.getElementById('modalCellId').value;
            const knowledgeLevel = document.getElementById('modalKnowledgeLevel').value;
            const language = document.getElementById('modalLanguage').value;

            if (language == 'french') {
                fetch(`${apiUrl}/update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // if Laravel CSRF protection
                        },
                        body: JSON.stringify({
                            id: cellId,
                            french: document.getElementById('modalInput').value,
                            correctly_translated: document.getElementById('modalKnowledgeLevel').value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            currentCellSpan.textContent = document.getElementById('modalInput').value;
                            closeModal();
                        } else {
                            document.getElementById('modalError').style.display = 'block';
                        }
                    })
                    .catch(error => {
                        document.getElementById('modalError').style.display = 'block';
                    });
            } else {
                fetch(`${apiUrl}/update`, {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}' // if Laravel CSRF protection
                        },
                        body: JSON.stringify({
                            id: cellId,
                            serere: document.getElementById('modalInput').value,
                            correctly_understood: document.getElementById('modalKnowledgeLevel').value
                        })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            currentCellSpan.textContent = document.getElementById('modalInput').value;
                            closeModal();
                        } else {
                            document.getElementById('modalError').style.display = 'block';
                        }
                    })
                    .catch(error => {
                        document.getElementById('modalError').style.display = 'block';
                    });
            }
        }


        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && document.getElementById('editCellModale').style.display === "flex") {
                saveChanges();
            }

            if (event.key === 'Escape' && document.getElementById('editCellModale').style.display === "flex") {
                closeModal();
            }
        });

        document.addEventListener('DOMContentLoaded', function() {
            seedTables();
        });
    </script>

</body>

</html>
