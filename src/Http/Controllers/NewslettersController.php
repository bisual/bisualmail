<?php

namespace bisual\bisualmail\Http\Controllers;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\App;
use bisual\bisualmail\bisualmail;
use bisual\bisualmail\Models\Newsletter;

class NewslettersController extends Controller
{
    public function __construct()
    {
        abort_unless(
            App::environment(config('bisualmail.allowed_environments', ['local'])),
            403
        );
    }

    public function index()
    {
        $active_item = 'mails';
        $nocard = 1;

        $newsletters = bisualmail::getNewsletters();

        return View(bisualmail::$view_namespace.'::sections.newsletters', compact('newsletters', 'active_item', 'nocard'));
    }

    public function new($type, $name, $skeleton)
    {
        $active_item = 'mails';
        $nocard = 1;
        $type = $type === 'html' ? $type : 'markdown';

        $skeleton = bisualmail::getTemplateSkeleton($type, $name, $skeleton);

        return View(bisualmail::$view_namespace.'::sections.create-template', compact('skeleton', 'active_item', 'nocard'));
    }

    public function view($newsletter_id = null)
    {
        $active_item = 'mails';
        $nocard = 1;

        $templates = bisualmail::getTemplateSkeletons()['html'];

        $newsletter = bisualmail::getNewsletter($newsletter_id);
        return View(bisualmail::$view_namespace.'::sections.edit-newsletter', compact('newsletter', 'active_item', 'nocard', 'templates'));
    }

    public function create(Request $request)
    {
        $date = Carbon::createFromFormat('d/m/Y H:i', $request->send_date['date'].' '.$request->send_date['time']);
        
        $new = new Newsletter();
        $new->title = $request->title;
        $new->send_date = $date;
        $new->filters = $request->filters;
        $new->save();

        return redirect()->route('backetfy.mails.viewNewsletter', ['newsletter_id' => $new->id]); 
    }

    public function select(Request $request)
    {
        $active_item = 'mails';
        $nocard = 1;

        return View(bisualmail::$view_namespace.'::sections.new-newsletter', compact('active_item', 'nocard'));
    }

    public function previewTemplateMarkdownView(Request $request)
    {
        return bisualmail::previewMarkdownViewContent(false, $request->markdown, $request->name, true);
    }

    public function delete(Request $request)
    {
        $new = Newsletter::find($request->id);
        if ($new) {
            $new->delete();

            return response()->json([
                'status' => 'ok',
            ]);
        } else {
            return response()->json([
                'status' => 'error',
            ]);
        }
    }

    public function update(Request $request)
    {
        return bisualmail::updateNewsletter($request);
    }

    public function parseNewsletter(Request $request)
    {
        $newsletter = Newsletter::where('id', $request->newsletter)->first();
        if ($request->newTemplate) {
            $template = explode('/', $request->selectedTemplate);
            $template = bisualmail::getTemplateSkeleton('html', $template[0], $template[1]);
    
            $bladeRenderable = preg_replace('/((?!{{.*?-)(&gt;)(?=.*?}}))/', '>', $template['template']);
            $newsletter->content = $bladeRenderable;
            $newsletter->save();
            return response()->json([
                'status' => 'ok',
            ]);
        }
        
        $bladeRenderable = preg_replace('/((?!{{.*?-)(&gt;)(?=.*?}}))/', '>', $request->markdown);
        $newsletter->content = $bladeRenderable;
        $newsletter->save();

        return response()->json([
            'status' => 'ok',
        ]);
    }
}
