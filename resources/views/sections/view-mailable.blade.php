@extends('bisualmail::layout.app')

@section('title', 'View Mailable')

@section('content-mails')

<div class="col-lg-10 col-md-12">             
                <div class="card my-4">
                    <div class="card-header d-flex align-items-center justify-content-between"><h5>Detalles</h5>
                    </div>
                    <div class="card-body card-bg-secondary">
                        <table class="table mb-0 table-borderless">
                            <tbody>
                                <tr>
                                    <td class="table-fit font-weight-sixhundred">Nombre</td>
                                    <td>
                                        {{ $mailable['name'] }}
                                    </td>
                                </tr>
                                @if (auth()->user()->hasRole('super-admin'))
                                <tr>
                                    <td class="table-fit font-weight-sixhundred">Namespace</td>
                                    <td>
                                        {{ $mailable['namespace'] }}
                                    </td>
                                </tr>
                                @endif

                                @if ( !empty($mailable['data']->subject) )
				    				<tr>
	                                    <td class="table-fit font-weight-sixhundred">Asunto</td>
	                                    <td>
	                                        {{ $mailable['data']->subject }}
	                                    </td>
                                	</tr>
				    			@endif

                                @if ( !empty($mailable['data']->locale) )
				    				<tr>
	                                    <td class="table-fit font-weight-sixhundred">Idioma</td>
	                                    <td>
	                                        {{ $mailable['data']->locale }}
	                                    </td>
                                	</tr>
				    			@endif

				    				{{-- <tr>
	                                    <td class="table-fit font-weight-sixhundred">De</td>
	                                    <td><a href="mailto:{{ !collect($mailable['data']->from)->isEmpty() ? collect($mailable['data']->from)->first()['address'] : config('mail.from.address') }}" class="badge badge-info mr-1 font-weight-light">
	                                    	@if (!collect($mailable['data']->from)->isEmpty())

                            					{{ collect($mailable['data']->from)->first()['address'] }}

                            					@else
											
												{{ config('mail.from.address') }} (default)

                            				@endif
                        				</a></td>
                                	</tr> --}}

                                	{{-- <tr>
	                                    <td class="table-fit font-weight-sixhundred">Reply To</td>
	                                    <td><a href="mailto:{{ !collect($mailable['data']->replyTo)->isEmpty() ? collect($mailable['data']->replyTo)->first()['address'] : config('mail.reply_to.address') }}" class="badge badge-info mr-1 font-weight-light">
	                                    	@if (!collect($mailable['data']->replyTo)->isEmpty())

                            					{{ collect($mailable['data']->replyTo)->first()['address'] }}

                            					@else
											
												{{ config('mail.reply_to.address') }} (default)

                            				@endif
                        				</a></td>

                                	</tr> --}}

                                @if ( !empty($mailable['data']->cc) )
                                <tr>
                                    <td class="table-fit font-weight-sixhundred">cc</td>
                                    <td>
                                    	@foreach( $mailable['data']->cc as $cc )
                                        <a href="mailto:{{ $cc['address'] }}" class="badge badge-info mr-1 font-weight-light">{{ $cc['address'] }}</a>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif
                                @if ( !empty($mailable['data']->bcc) )
                                <tr>
                                    <td class="table-fit font-weight-sixhundred">bcc</td>
                                    <td>
                                    	@foreach( $mailable['data']->bcc as $bcc )
                                        <a href="mailto:{{ $bcc['address'] }}" class="badge badge-info mr-1 font-weight-light">{{ $bcc['address'] }}</a>
                                        @endforeach
                                    </td>
                                </tr>
                                @endif

                                <tr>
                                    <td class="table-fit font-weight-sixhundred">Selecciona una plantilla</td>
                                    <td>
                                        <select name="template" id="template-select" style="width: 100%;">
                                            @if ($currentTemplate == 'view.name')
                                            <option selected value="view.name">No tiene plantilla asociada</option>
                                            @endif
                                            @foreach ($templates as $temp)
                                                <option @if ($temp->template_slug == $currentTemplate) selected @endif value="{{ $temp->template_slug }}">{{ $temp->template_name }}</option>
                                            @endforeach
                                        </select>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card my-4">
                    <div class="card-header d-flex align-items-center justify-content-between"><h5>Vista previa</h5>
                    	@if ( !is_null($mailable['view_path']) )
                    		<a class="btn btn-primary" href="{{ route('backetfy.mails.editMailable', ['name' => $mailable['name']]) }}">Editar plantilla</a>
                    	@endif
                    	
                    </div>
                    <div class="embed-responsive embed-responsive-16by9">
					  <iframe class="embed-responsive-item" src="{{ route('backetfy.mails.previewMailable', [ 'name' => $mailable['name'] ]) }}" allowfullscreen></iframe>
					</div>
                </div>
            </div>

<script type="text/javascript">
$('#template-select').change(() => {
    let current = '{{ $currentTemplate }}';
    console.log(current);
    
    if (current != $('#template-select').val()) {
        notie.confirm({
            text: '¿Seguro que quieres cambiar la plantilla de esta email?',
            submitCallback: function () {
                axios.post('{{ route('backetfy.mails.parseTemplate') }}', {
                    onlyChangeView: true,
                    selectedTemplate: $('#template-select').val(),
                    currentMailable: '{{ $currentMail }}',
                    currentTemplate: '{{ $currentTemplate }}'
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
                let current = '{{ $currentTemplate }}';
                $('#template-select').val(current).trigger('change');
            }
        })
    }
});
</script>
   
@endsection