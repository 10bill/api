<?php

namespace App\Http\Controllers\Api;


use App\Http\Controllers\Controller;
use App\Http\Requests\CreatePostRequest;
use App\Http\Requests\EditPostRequest;
use Illuminate\Http\Request;
use App\Models\Post;
use Exception;
use GuzzleHttp\Promise\Create;

class PostController extends Controller
{
    // Liste des posts
    public function index(Request $request)
    {
        try {
            $query = Post::query();
            $perPage = 2;
            $page = $request->input('page', 1);
            $search = $request->input('search');

            if ($search) {
                $query->whereRaw("titre Like ?", ['%' . $search . '%']);
            }

            $total = $query->count();
            $result = $query->offset(($page - 1) * $perPage)->limit($perPage)->get();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Les posts ont été récupérés avec succès.',
                'current_page' => $page,
                'last_page' => ceil($total / $perPage),
                'items' => $result
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la récupération des posts.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }

    // Ajouter un post
    public function store(CreatePostRequest $request)
    {
        try {
            $post = new Post();
            $post->titre = $request->titre;
            $post->description = $request->description;
            $post->user_id = auth()->user()->id;
            $post->save();

            return response()->json([
                'status_code' => 201,
                'status_message' => 'Le post a été ajouté avec succès.',
                'data' => $post
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la création du post.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }

    // Modifier un post
    public function update(EditPostRequest $request, Post $post)
    {
        try {
            if ($post->user_id !== auth()->id()) {
                return response()->json([
                    'status_code' => 403,
                    'status_message' => "Vous n'êtes pas autorisé à modifier ce post.",
                ], 403);
            }

            $post->titre = $request->titre;
            $post->description = $request->description;
            $post->save();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Le post a été mis à jour avec succès.',
                'data' => $post,
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la mise à jour du post.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }

    // Supprimer un post
    public function delete(Post $post)
    {
        try {
            if ($post->user_id !== auth()->id()) {
                return response()->json([
                    'status_code' => 403,
                    'status_message' => "Vous n'êtes pas autorisé à supprimer ce post.",
                ], 403);
            }

            $post->delete();

            return response()->json([
                'status_code' => 200,
                'status_message' => 'Le post a été supprimé avec succès.',
            ]);
        } catch (Exception $e) {
            return response()->json([
                'status_code' => 500,
                'status_message' => 'Erreur lors de la suppression du post.',
                'error_details' => $e->getMessage(),
            ], 500);
        }
    }
}