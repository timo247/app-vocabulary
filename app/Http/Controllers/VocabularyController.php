<?php

namespace App\Http\Controllers;

use App\Models\Vocabulary;
use Illuminate\Http\Request;

class VocabularyController extends Controller
{
    /**
     * Affiche la liste des phrases.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $vocabularies = Vocabulary::all(); // Récupère toutes les phrases
        return view('home', compact('vocabularies')); // Retourne une vue avec les phrases
    }

    public function update(Request $request)
    {
        // Validation des données de la requête
        $request->validate([
            'id' => 'required|integer',
            'french' => 'nullable|string',
            'serere' => 'nullable|string',
            'correctly_translated' => 'nullable|numeric',
            'correctly_understood' => 'nullable|numeric',
        ]);

        // Recherche du vocabulaire à partir de l'ID
        $vocabulary = Vocabulary::find($request->id);
        if ($vocabulary) {
            // Mise à jour des champs si présents dans la requête
            if ($request->has('serere')) {
                $vocabulary->serere = $request->serere;
                if ($request->correctly_understood < 0) {
                    $vocabulary->correctly_understood = 0;
                } else {
                    $vocabulary->correctly_understood = $request->correctly_understood;
                }
            }
            if ($request->has('french')) {
                $vocabulary->french = $request->french;
                if ($request->correctly_understood < 0) {
                    $vocabulary->correctly_translated = 0;
                } else {
                    $vocabulary->correctly_translated = $request->correctly_translated;
                }
            }
            $vocabulary->save();
            return response()->json(['success' => true, 'message' => 'Update successful']);
        }
        return response()->json(['success' => false, 'message' => 'No element found'], 404);
    }


    // /**
    //  * Affiche le formulaire de création d'une nouvelle phrase.
    //  *
    //  * @return \Illuminate\Http\Response
    //  */
    // public function create()
    // {
    //     return view('vocabulary.create'); // Retourne une vue avec le formulaire de création
    // }

    // /**
    //  * Enregistre une nouvelle phrase dans la base de données.
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  * @return \Illuminate\Http\Response
    //  */
    // public function store(Request $request)
    // {
    //     $validated = $request->validate([
    //         'french' => 'required|string|max:255',
    //         'serere' => 'required|string|max:255',
    //         'translation_knowledge' => 'required|integer',
    //         'understanding_knowledge' => 'required|integer',
    //     ]);

    //     Vocabular::create($validated); // Crée une nouvelle entrée dans la base de données
    //     return redirect()->route('vocabulary.index'); // Redirige vers la liste des phrases
    // }

    // /**
    //  * Affiche les détails d'une phrase spécifique.
    //  *
    //  * @param int $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function show($id)
    // {
    //     $Vocabular = Vocabular::findOrFail($id); // Trouve la phrase par ID ou renvoie une erreur 404
    //     return view('vocabulary.show', compact('Vocabular')); // Retourne une vue avec les détails de la phrase
    // }

    // /**
    //  * Affiche le formulaire d'édition pour une phrase spécifique.
    //  *
    //  * @param int $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function edit($id)
    // {
    //     $Vocabular = Vocabular::findOrFail($id); // Trouve la phrase par ID ou renvoie une erreur 404
    //     return view('vocabulary.edit', compact('Vocabular')); // Retourne une vue avec le formulaire d'édition
    // }

    // /**
    //  * Met à jour une phrase spécifique dans la base de données.
    //  *
    //  * @param \Illuminate\Http\Request $request
    //  * @param int $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function update(Request $request, $id)
    // {
    //     $validated = $request->validate([
    //         'french' => 'required|string|max:255',
    //         'serere' => 'required|string|max:255',
    //         'translation_knowledge' => 'required|integer',
    //         'understanding_knowledge' => 'required|integer',
    //     ]);

    //     $Vocabular = Vocabular::findOrFail($id); // Trouve la phrase par ID ou renvoie une erreur 404
    //     $Vocabular->update($validated); // Met à jour la phrase avec les données validées
    //     return redirect()->route('vocabulary.index'); // Redirige vers la liste des phrases
    // }

    // /**
    //  * Supprime une phrase spécifique de la base de données.
    //  *
    //  * @param int $id
    //  * @return \Illuminate\Http\Response
    //  */
    // public function destroy($id)
    // {
    //     $Vocabular = Vocabular::findOrFail($id); // Trouve la phrase par ID ou renvoie une erreur 404
    //     $Vocabular->delete(); // Supprime la phrase
    //     return redirect()->route('vocabulary.index'); // Redirige vers la liste des phrases
    // }
}
