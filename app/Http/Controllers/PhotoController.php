<?php

namespace App\Http\Controllers;

use App\Http\Apis\PhotoApi;

/**
 * A UI controller for the Photo model and resources.
 *
 * This controller introduces hierarchy in the MVC's
 * controller layer such that this controller consumes
 * the underlying PhotoApi. Other ways of consuming
 * the API could be used to avoid inheritance conflicts
 * but that is not a primary concern for this article.
 */
class PhotoController extends PhotoApi
{
    /**
     * Show an upload form so that images can be uploaded.
     *
     * @return \Illuminate\Views\View
     */
    public function uploadForm()
    {
        return view('upload');
    }

    /**
     * Process the upload form and redirect to the photo.
     *
     * @return \Illuminate\Http\RedirectResponse
     */
    public function upload()
    {
        // Use the PhotoApi to store the form data
        // and get the Photo model back.
        $photo = parent::store($form);

        // Now redirect to the photo's page with
        // a flash message indicating success.
        return redirect()
            ->to('photos/' . $photo->id)
            ->with('message', 'Photo uploaded!');
    }
}
