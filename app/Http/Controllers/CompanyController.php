<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\UpdateCompanyRequest;
use App\Models\Company;
use App\Models\Image;

class CompanyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $company = Auth::user()->company;
        
        return $company->id == $id ? view('edit_company', compact('company')) : redirect()->route('home');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompanyRequest $request, $id)
    {
        $company = Auth::user()->company;

        if ($company->id == $id) {
            $company->update($request->all());
            $avatar = $request->avatar;

            if (isset($avatar) && ($avatar->getMimeType() === config('user.png') || $avatar->getMimeType() === config('user.jpg'))) {
                $urlAvt = $avatar->move(public_path() . config('user.upload_company'), date(config('user.date_time')) . $avatar->getClientOriginalName());
                $image = $company->images()->where('type', config('user.avatar'))->first();;

                if ($image) {
                    $image->url = $urlAvt;
                    $image->save();
                } else {
                    Image::create([
                        'url' => $urlAvt,
                        'imageable_id' => $company->id,
                        'imageable_type' => Company::class,
                        'type' => config('user.avatar'),
                    ]);
                }
            }
            $company->save();
        }

        return redirect()->route('home');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
