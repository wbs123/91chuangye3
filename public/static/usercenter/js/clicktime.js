//时间
$('#dd').calendar({
	trigger: '#dt',
	zIndex: 999,
	format: 'yyyy'
//	  onSelected: function(view, date, data) {
//	    console.log('event: onSelected')
//	  },
//	  onClose: function(view, date, data) {
//	    console.log('event: onClose')
//	    console.log('view:' + view)
//	    console.log('date:' + date)
//	    console.log('data:' + (data || 'None'));
//	  }
});
$('#dd1').calendar({
	trigger: '#dt1',
	zIndex: 999,
	format: 'yyyy'
});

$('#dd2').calendar({
	trigger: '#dt2',
	zIndex: 999,
	format: 'yyyy'
});

$('#dd3').calendar({
	trigger: '#dt3',
	zIndex: 999,
	format: 'yyyy'
});

$('#dd4').calendar({
	trigger: '#dt4',
	zIndex: 999,
	format: 'yyyy'
});

$('#dd5').calendar({
	trigger: '#dt5',
	zIndex: 999,
	format: 'yyyy',
});

$('#dd6').calendar({
	trigger: '#company_buildtime',
	zIndex: 999,
	format: 'yyyy-mm-dd',
});