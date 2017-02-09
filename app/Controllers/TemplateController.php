<?php

namespace App\Controllers;

use App\Http\Requests\Template\CreateOrUpdateTemplate;
use App\Models\Template;
use Illuminate\Http\Request;

class TemplateController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function index()
    {
        $templates = Template::all();

        return view('template.index')->with(compact('templates'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('template.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param CreateOrUpdateTemplate|Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(CreateOrUpdateTemplate $request)
    {
        Template::create($request->only((new Template)->getFillable()));

        return redirect()->route('template.index');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Template $template
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Http\Response|\Illuminate\View\View
     */
    public function edit(Template $template)
    {
        return view('template.edit')->with(compact('template'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param CreateOrUpdateTemplate|Request $request
     * @param  \App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function update(CreateOrUpdateTemplate $request, Template $template)
    {
        $template->update($request->only($template->getFillable()));

        return redirect()->route('template.index');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Template $template
     * @return \Illuminate\Http\Response
     */
    public function destroy(Template $template)
    {
        $template->delete();

        return redirect()->route('template.index');
    }
}
