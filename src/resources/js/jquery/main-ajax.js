var ajaxRun = true;

$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content'),
  },
  beforeSend: (xhr, opts) => {

  	if (ajaxRun)
  		ajaxRun = false;
  	else
  		xhr.abort();
  },
  complete: () => {
  	ajaxRun = true;
  }
});