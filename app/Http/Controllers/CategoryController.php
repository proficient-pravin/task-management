<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\JsonResponse;

class CategoryController extends Controller
{
    /**
     * Display a listing of the categories.
     *
     * @return \Illuminate\Database\Eloquent\Collection|JsonResponse
     */
    public function index()
    {
        try {
            $categories = Category::where('created_by', auth()->user()->id)->get();
            return $this->successResponse('Categories retrieved successfully.', $categories);
        } catch (\Exception $e) {
            Log::error('Error retrieving categories: ' . $e->getMessage());
            return $this->errorResponse('Internal server error.', 500);
        }
    }

    /**
     * Store a newly created category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request)
    {
        try {
            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Create the category with validated data
            $postData = $request->only('name');
            $postData['created_by'] = auth()->user()->id;
            $category = Category::create($postData);

            // Log the creation of the new category
            Log::info('Category created successfully.', ['category_id' => $category->id, 'name' => $category->name]);

            // Return the created category
            return $this->successResponse('Category created successfully.', $category, 201); // Respond with 201 Created status
        } catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->validator);            
        }catch (\Exception $e) {
            Log::error('Error creating category: ' . $e->getMessage());
            return $this->errorResponse('Internal server error.', 500);
        }
    }

    /**
     * Update the specified category in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, $id)
    {
        try {
            // Find the category by ID, or return 404 if not found
            $category = Category::findOrFail($id);

            // Validate the incoming request
            $request->validate([
                'name' => 'required|string|max:255',
            ]);

            // Update the category with validated data
            $category->update($request->only('name'));

            // Log the update action
            Log::info('Category updated successfully.', ['category_id' => $category->id, 'name' => $category->name]);

            // Return the updated category
            return $this->successResponse('Category updated successfully.', $category); // Respond with 200 OK status
        }catch (\Illuminate\Validation\ValidationException $e) {
            return $this->validationErrorResponse($e->validator);            
        } catch (\Exception $e) {
            Log::error('Error updating category: ' . $e->getMessage());
            return $this->errorResponse('Internal server error.', 500);
        }
    }

    /**
     * Remove the specified category from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy($id)
    {
        try {
            // Find the category by ID, or return 404 if not found
            $category = Category::findOrFail($id);

            // Delete the category
            $category->delete();

            // Log the deletion
            Log::info('Category deleted successfully.', ['category_id' => $category->id, 'name' => $category->name]);

            // Respond with a 204 No Content status
            return $this->successResponse('Category deleted successfully.', null, 204);
        } catch (\Exception $e) {
            Log::error('Error deleting category: ' . $e->getMessage());
            return $this->errorResponse('Internal server error.', 500);
        }
    }
}
