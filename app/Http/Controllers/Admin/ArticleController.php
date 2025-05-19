<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ArticleController extends Controller
{
    public function index()
    {
        $articles = Article::all();
        return view('admin.articles.index', compact('articles'));
    }

    public function create()
    {
        return view('admin.articles.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'required|image|mimes:jpeg,png,jpg|max:2048',
            'weight_text' => 'required|string|max:255',
            'type' => 'required|array|min:1',
            'usage' => 'nullable|array',
            'weight_class' => 'required|string|in:leger,moyen,lourd,variable',
            'average_weight' => 'required|numeric|min:0'
        ]);

        // Générer le texte du prix basé sur le poids
        $validated['price_text'] = $this->calculatePriceText($validated['average_weight'], $validated['weight_class']);

        // Générer un slug à partir du nom de l'article
        $slug = Str::slug($validated['name']);

        // Gérer le téléchargement de l'image
        if ($request->hasFile('image')) {
            $image = $request->file('image');
            
            // Obtenir le premier type d'article pour l'utiliser dans le chemin
            $articleType = $validated['type'][0];
            
            // Créer le répertoire s'il n'existe pas
            $directory = "img/art/{$articleType}";
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            
            // Générer un nom de fichier unique avec le slug
            $imageName = $slug . '-' . time() . '.' . $image->getClientOriginalExtension();
            
            // Déplacer l'image dans le répertoire
            $image->move(public_path($directory), $imageName);
            
            $validated['image_path'] = $directory . '/' . $imageName;
        }

        // Créer l'article
        Article::create($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article créé avec succès !');
    }

    public function show(Article $article)
    {
        return view('admin.articles.show', compact('article'));
    }

    public function edit(Article $article)
    {
        return view('admin.articles.edit', compact('article'));
    }

    public function update(Request $request, Article $article)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:jpeg,png,jpg|max:2048',
            'weight_text' => 'required|string|max:255',
            'type' => 'required|array|min:1',
            'usage' => 'nullable|array',
            'weight_class' => 'required|string|in:leger,moyen,lourd,variable',
            'average_weight' => 'required|numeric|min:0'
        ]);

        // Générer le texte du prix basé sur le poids
        $validated['price_text'] = $this->calculatePriceText($validated['average_weight'], $validated['weight_class']);

        // Générer un slug à partir du nom de l'article
        $slug = Str::slug($validated['name']);

        // Gérer le téléchargement de l'image
        if ($request->hasFile('image')) {
            // Supprimer l'ancienne image si elle existe
            if ($article->image_path && file_exists(public_path($article->image_path))) {
                unlink(public_path($article->image_path));
            }
            
            $image = $request->file('image');
            
            // Obtenir le premier type d'article pour l'utiliser dans le chemin
            $articleType = $validated['type'][0];
            
            // Créer le répertoire s'il n'existe pas
            $directory = "img/art/{$articleType}";
            if (!file_exists(public_path($directory))) {
                mkdir(public_path($directory), 0755, true);
            }
            
            // Générer un nom de fichier unique avec le slug
            $imageName = $slug . '-' . time() . '.' . $image->getClientOriginalExtension();
            
            // Déplacer l'image dans le répertoire
            $image->move(public_path($directory), $imageName);
            
            $validated['image_path'] = $directory . '/' . $imageName;
        }

        // Mettre à jour l'article
        $article->update($validated);

        return redirect()->route('admin.articles.index')
            ->with('success', 'Article mis à jour avec succès !');
    }

    public function destroy(Article $article)
    {
        // Supprimer l'image si elle existe
        if ($article->image_path && file_exists(public_path($article->image_path))) {
            unlink(public_path($article->image_path));
        }
        
        $article->delete();
        
        return redirect()->route('admin.articles.index')
            ->with('success', 'Article supprimé avec succès !');
    }

    /**
     * Calcule le texte du prix basé sur le poids moyen et la classe de poids
     */
    private function calculatePriceText($averageWeight, $weightClass)
    {
        // Prix de base par kg selon la classe de poids
        $basePrices = [
            'leger' => 5.00,    // 5€/kg pour les articles légers
            'moyen' => 4.50,    // 4.50€/kg pour les articles moyens
            'lourd' => 4.00,    // 4€/kg pour les articles lourds
            'variable' => 4.50  // 4.50€/kg pour les articles variables
        ];

        // Calculer le prix estimé
        $estimatedPrice = $averageWeight * $basePrices[$weightClass];
        
        // Formater le prix avec 2 décimales
        return number_format($estimatedPrice, 2) . ' €';
    }
} 