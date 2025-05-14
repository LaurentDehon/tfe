<?php

namespace App\Http\Controllers;

use App\Models\Concept;
use Illuminate\Http\Request;

class WikiController extends Controller
{
    /**
     * Affiche la liste des concepts dans une page wiki.
     *
     * @return \Illuminate\View\View
     */
    public function concepts()
    {
        // Récupère tous les concepts triés par ordre alphabétique
        $concepts = Concept::orderBy('name')->get();
        
        // Groupe les concepts par première lettre pour l'affichage alphabétique
        $groupedConcepts = $concepts->groupBy(function($concept) {
            return strtoupper(substr($concept->name, 0, 1));
        })->sortKeys();
        
        return view('wiki.concepts', compact('groupedConcepts'));
    }
}
