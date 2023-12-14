@extends('bisualmail::layout.app')

@section('title', 'Edit Newsletter')

@section('content')

     <style type="text/css">
         
        .CodeMirror {
            height: 400px;
        }

        .editor-preview-active,
        .editor-preview-active-side {
            /*display:block;*/
        }
        .editor-preview-side>p,
        .editor-preview>p {
            margin:inherit;
        }
        .editor-preview pre,
        .editor-preview-side pre {
             background:inherit;
             margin:inherit;
        }
        .editor-preview table td,
        .editor-preview table th,
        .editor-preview-side table td,
        .editor-preview-side table th {
         border:inherit;
         padding:inherit;
        }
        .view_data_param {
            cursor: pointer;
        }

     </style>

<div class="col-lg-12 col-md-12">
        <div class="container">
            <div class="row my-4">
                <div class="col-12 mb-2 d-block d-lg-none">
                    <div id="accordion">
                      <div class="card">
                        <div class="card-header" id="headingOne">
                          <h5 class="mb-0 dropdown-toggle" style="cursor: pointer;" data-toggle="collapse" data-target="#collapseOne" aria-expanded="true" aria-controls="collapseOne">
                            Detalles
                          </h5>
                        </div>

                        <div id="collapseOne" class="collapse show" aria-labelledby="headingOne" data-parent="#accordion">
                          <div class="card-body">
                            <p style="font-size: .9em;"><b class="font-weight-sixhundred">Nombre:</b> {{ $newsletter['new']->title }}</p>
                            <p style="font-size: .9em;"><b class="font-weight-sixhundred">Fecha:</b> {{ $newsletter['new']->send_date->format('d \d\e M \d\e Y \a \l\a\s h:i') }}</p>
							@foreach ($newsletter['filters'] as $key => $item)
							<p style="font-size: .9em;">
								<b class="font-weight-sixhundred">{{ $key }}:</b>
								{{$item}}
							</p>
							@endforeach
							<p class="text-primary edit-newsletter" style="cursor:pointer;"><i class="fas fa-trash"></i> Editar detalles</p>
                            <span class="text-danger delete-newsletter" style="cursor:pointer;"><i class="fas fa-trash "></i> Eliminar newsletter</p>
                        </div>
                        </div>
                      </div>
                    </div>
                </div>
                <div class="col-lg-9 col-md-12">
                    <div class="card mb-2">
                        <div class="card-header p-3" style="border-bottom:1px solid #e7e7e7e6;">
                            <button type="button" class="btn btn-success float-right save-newsletter">Actualizar</button>
                            <button type="button" class="btn btn-secondary float-right preview-toggle mr-2"><i class="far fa-eye"></i> Vista previa</button>
                            <button type="button" class="btn btn-light float-right mr-2 save-draft disabled">Guardar borrador</button>
                        </div>
                    </div>

                    <div class="card">
						<select name="template[html]" id="template-select" style="width: 100%;">
							@foreach ($templates as $type => $aux)
								@foreach ($aux as $key => $temp)
								<option value="{{ $type.'/'.$temp }}">{{ $type.'.'.$temp }}</option>
								@endforeach
							@endforeach
						</select>
                    
						<ul class="nav nav-pills" id="pills-tab" role="tablist">
						<li class="nav-item">
							<a class="nav-link active" id="pills-home-tab" data-toggle="pill" href="#pills-home" role="tab" aria-controls="pills-home" aria-selected="true">Editor</a>
						</li>
						</ul>
						<div class="tab-content" id="pills-tabContent">
						<div class="tab-pane fade show active" id="pills-home" role="tabpanel" aria-labelledby="pills-home-tab">
							<textarea id="newsletter_editor" cols="30" rows="10">{{ $newsletter['new']->content }}</textarea>
						</div>
						</div>
                    </div>
                </div>
                <div class="col-lg-3 d-none d-lg-block">
                    <div class="card">
                        <div class="card-header"><h5>Detalles</h5></div>
                        <div class="card-body">
							<p style="font-size: .9em;"><b class="font-weight-sixhundred">Nombre:</b> {{ $newsletter['new']->title }}</p>
							<p style="font-size: .9em;"><b class="font-weight-sixhundred">Fecha:</b> {{ $newsletter['new']->send_date->format('d \d\e M \d\e Y \a \l\a\s h:i') }}</p>
							@foreach ($newsletter['filters'] as $key => $item)
							<p style="font-size: .9em;">
								<b class="font-weight-sixhundred">{{ $key }}:</b>
								{{$item}}
							</p>
							@endforeach
							<p class="text-primary edit-newsletter" style="cursor:pointer;"><i class="fas fa-trash"></i> Editar detalles</p>
                            <span class="text-danger delete-newsletter" style="cursor:pointer;"><i class="fas fa-trash "></i> Eliminar newsletter</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>       
 </div>
<script type="text/javascript">

$(document).ready(function(){

	var newsletterID = "{{ "newsletter_view_".$newsletter['new']->id }}";

	$('.edit-newsletter').click(function(){

		notie.input({
			text: 'Cambiar título:',
			type: 'text',
			submitText: 'Actualizar',
			cancelText: 'Cancelar',
			placeholder: 'ej. Abril para academias.',
			allowed: new RegExp('[^a-zA-Z0-9 ]', 'g'),
			submitCallback: function (newslettername) {
				axios.post('{{ route('backetfy.mails.updateNewsletter') }}', {
					fromDetails: 1,
					newsletter_id: '{{ $newsletter["new"]->id }}',
					title: newslettername,
				})
				.then(function (response) {
					if (response.data.status === 'ok'){
						let url = '{{ route("backetfy.mails.viewNewsletter", ["newsletter_id" => "_id"]) }}';
						window.location.replace(url.replace('_id', response.data.data.id));
					} else {
						alert(response.data.message);
					}

				})

				.catch(function (error) {
					notie.alert({ type: 'error', text: error, time: 2 })
				});
            }
        });
	});

	$('.delete-newsletter').click(function(){

		notie.confirm({

	        text: 'Are you sure you want to do that?',

	    	submitCallback: function () {

	    		axios.post('{{ route('backetfy.mails.deleteNewsletter') }}', { 
				  	id: '{{ $newsletter["new"]->id }}',
				  })

		    .then(function (response) {
		        
		    	if (response.data.status == 'ok'){
				    	
		    		notie.alert({ type: 1, text: 'Newsletter eliminada <br><small>Redirigiendo...</small>', time: 3 })

				    setTimeout(function(){
                                window.location.replace('{{ route('backetfy.mails.newsletters') }}');
                    }, 3000);

			    } else {
			    	
			    	notie.alert({ type: 'error', text: 'Newsletter no eliminada', time: 3 })
			    }
		        
		    })

		    .catch(function (error) {
		        notie.alert({ type: 'error', text: error, time: 3 })
		    });

		   }
		});

	});

	tinymce.init({
		language: 'es',
		selector: "textarea#newsletter_editor",
		menubar : false,
		visual: false,
		height:600,
		inline_styles : true,
		plugins: [
				"advlist autolink link image lists charmap print preview hr anchor pagebreak spellchecker",
				"searchreplace wordcount visualblocks visualchars code fullscreen insertdatetime media nonbreaking",
				"save table directionality emoticons newsletter paste fullpage code legacyoutput"
		],
		content_css: "css/content.css",
		toolbar: "insertfile undo redo | link image | styleselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | fullpage table | forecolor backcolor emoticons | preview | code",
		fullpage_default_encoding: "UTF-8",
		fullpage_default_doctype: "<!DOCTYPE html>",
		link_context_toolbar: true,
		init_instance_callback: function (editor) 
		{
			editor.on('Change', function (e) {
				if ($('.save-draft').hasClass('disabled')){
					$('.save-draft').removeClass('disabled').text('Guardar borrador');
				}
			});

			if (localStorage.getItem(newsletterID) !== null) {
				editor.setContent(localStorage.getItem(newsletterID));
			}

			setTimeout(function(){ 
				editor.execCommand("mceRepaint");
			}, 2000);

		}
	});


	$('.save-newsletter').click(function(){

		 notie.confirm({

	        text: '¿Seguro que quieres guardar la plantilla?',
			submitText: 'Guardar',
			cancelText: 'Cancelar',

	    	submitCallback: function () {

	    		axios.post('{{ route('backetfy.mails.parseNewsletter') }}', {
	    			markdown: tinymce.get('newsletter_editor').getContent(),
					newsletter: "{{ $newsletter['new']->id }}"
	    		})

		    .then(function (response) {
		        
		    	if (response.data.status == 'ok'){
				    	
		    		notie.alert({ type: 1, text: 'Plantilla actualizada', time: 3 })

				    localStorage.removeItem(newsletterID);
			    } else {
			    	
			    	notie.alert({ type: 'error', text: 'Plantilla no actualizada', time: 3 })
			    }
		        
		    })

		    .catch(function (error) {
		        notie.alert({ type: 'error', text: error, time: 3 })
		    });

		   }
		});

	});

	$('.save-draft').click(function(){
		if (!$('.save-draft').hasClass('disabled')){
			localStorage.setItem(newsletterID, tinymce.get('newsletter_editor').getContent());
			$(this).addClass('disabled').text('Borrador guardado');
		}
	});

	 $('.preview-toggle').click(function(){
		tinyMCE.execCommand('mcePreview');return false;
	});

});

$('#template-select').change(() => {
	notie.confirm({
		text: '¿Seguro que quieres cambiar la plantilla de esta newsletter?',
		submitCallback: function () {
			axios.post('{{ route('backetfy.mails.parseNewsletter') }}', {
				selectedTemplate: $('#template-select').val(),
				markdown: tinymce.get('newsletter_editor').getContent(),
				newsletter: "{{ $newsletter['new']->id }}",
				newTemplate: 1
			}).then(function (response) {
				if (response.data.status == 'ok'){
					notie.alert({ type: 1, text: 'Plantilla actualizada', time: 1 })
					setTimeout(() => {
						location.reload();
					}, 1100)
				} else {
					notie.alert({ type: 'error', text: 'Plantilla no actializada', time: 3 })
				}
			}).catch(function (error) {
				notie.alert({ type: 'error', text: error, time: 2 })
			});
		},
		cancelCallback: () => {
		}
	})
});
                
</script>
   
@endsection