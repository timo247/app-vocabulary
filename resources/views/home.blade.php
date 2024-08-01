<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vocabulaire sérère</title>
    <style>
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

    <table class="dynamicTable">
        <thead>
            <tr>
                <th>Français</th>
                <th>Sérère</th>
                <th class="hidden">Action</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>
                    <div class="cell-content">
                        <button class="edit-button" onclick="editCell(this)">✏️</button>
                        <div class="cell-clickable-area" onclick="changeCellColor(this)" data-color="white">
                            <span>Exemple 1</span>
                        </div>
                    </div>
                </td>
                <td>
                    <div class="cell-content">
                        <button class="edit-button" onclick="editCell(this)">✏️</button>
                        <div class="cell-clickable-area" onclick="changeCellColor(this)" data-color="white">
                            <span>Exemple 2</span>
                        </div>
                    </div>
                </td>
                <td class="no-border"><button class="delete-button" onclick="deleteRow(this)">Supprimer</button></td>
            </tr>
        </tbody>
    </table>

    <button class="add-button" onclick="addRow()">Ajouter une ligne</button>

    <!-- Modale -->
    <div id="editCellModale" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <p>Changer le texte:</p>
            <input type="text" id="modalInput">
            <button onclick="saveChanges()">OK</button>
        </div>
    </div>

    <script>
        var currentCell;

        function addRow() {
            // Obtenir le tableau par son ID
            const table = document.querySelector(".dynamicTable").getElementsByTagName('tbody')[0];

            // Créer une nouvelle ligne
            const newRow = table.insertRow();

            // Insérer deux nouvelles cellules dans la nouvelle ligne
            const cell1 = newRow.insertCell(0);
            const cell2 = newRow.insertCell(1);
            const cell3 = newRow.insertCell(2);

            // Ajouter du contenu aux nouvelles cellules
            //cell1.innerHTML ='<div class="cell-content"><button class="edit-button" onclick="editCell(this)">✏️</button><span>Nouvelle Cellule 1</span></div>';
            cell1.innerHTML =
                '<div class="cell-content"><button class="edit-button" onclick="editCell(this)">✏️</button><div class="cell-clickable-area" onclick="changeCellColor(this)" data-color="white"><span>Exemple 1</span></div></div>'
            cell2.innerHTML =
                '<div class="cell-content"><button class="edit-button" onclick="editCell(this)">✏️</button><span>Nouvelle Cellule 2</span></div>';
            cell3.innerHTML = '<button class="delete-button" onclick="deleteRow(this)">Supprimer</button>';
            cell3.classList.add("no-border");
        }

        function deleteRow(button) {
            // Obtenir la ligne à supprimer
            const row = button.parentNode.parentNode;
            // Supprimer la ligne
            row.parentNode.removeChild(row);
        }

        function editCell(button) {
            currentCell = button.parentNode.querySelector('span');
            document.getElementById('modalInput').value = currentCell.textContent;
            document.getElementById('editCellModale').style.display = "flex";
            document.getElementById('modalInput').focus()
        }

        function changeCellColor(cell) {
            console.log(cell)
            let cellColor = cell.dataset.color;
            if (cellColor == "white") {
                cell.dataset.color = "#6E69FF";
            } else if (cellColor == "#6E69FF") {
                cell.dataset.color = "#ef476f";
            } else if (cellColor == "#ef476f") {
                cell.dataset.color = "#ffd166";
            } else if (cellColor == "#ffd166") {
                cell.dataset.color = "#FFFA75";
            } else if (cellColor == "#FFFA75") {
                cell.dataset.color = "#AEFCB2";
            } else if (cellColor == "#AEFCB2") {
                cell.dataset.color = "white";
            }
            cell.parentNode.style.backgroundColor = `${cell.dataset.color}`;
            console.log(cell.parentNode)
        }

        function closeModal() {
            document.getElementById('editCellModale').style.display = "none";
        }

        function saveChanges() {
            // Mettre à jour le contenu de la cellule avec la nouvelle valeur
            currentCell.textContent = document.getElementById('modalInput').value;
            // Fermer la modale
            closeModal();
        }

        document.addEventListener('keydown', function(event) {
            if (event.key === 'Enter' && document.getElementById('editCellModale').style.display === "flex") {
                saveChanges();
            }

            if (event.key === 'Escape' && document.getElementById('editCellModale').style.display === "flex") {
                closeModal();
            }

        });
    </script>

</body>

</html>
