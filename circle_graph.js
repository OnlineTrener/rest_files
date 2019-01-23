function updateGraph()
	{
		let dta = new Date();
		let startYear = dta.getFullYear();
		let startMonth = dta.getMonth()+1;
		if ( startMonth < 10 ) { startMonth = parseInt('0'+startMonth); }
		let startDay = dta.getDate();
		if ( startDay < 10 ) { startDay = parseInt('0'+startDay); }
		let formatStartDate = startYear+'-'+startMonth+'-'+startDay;
		
		let endDate = new Date(Date.now() + 86400000);
		let endYear = endDate.getFullYear();
		let endMonth = endDate.getMonth()+1;
		if ( endMonth < 10 ) { endMonth = parseInt('0'+endMonth); }
		let endDay = endDate.getDate();
		if ( endDay < 10 ) { endDay = parseInt('0'+endDay); }
		let formatEndDate = endYear+'-'+endMonth+'-'+endDay;
		
		let missedAlertsActive = 0;
		if ( formatStartDate == todayFormatDate ) { missedAlertsActive = 1; } 
		window['gajax']('careplanpatient/getpatienttasks', { 'missed_alerts': missedAlertsActive, 'startDate': formatStartDate, 'endDate': formatEndDate, 'type': 0 },  function(data, status, xhr)
			{
				data = JSON.parse(data); 
				
				window['daily_tasks_done'] = data['daily_tasks_done'];
				window['upcomingTask'] = data['shedule_range'];
				window['alert_task'] = data['alert_task'];
				mainthis.mainModel['showAlerts'] = window['alert_task'].length;
				mainthis.mainModel['todayAppointment'] = false;
				if ( window['upcomingTask'] && window['upcomingTask'].length > 0 )
					{
						$.each( window['upcomingTask'], function(a, b){
							if ( b['type'] == 'call' && b['time_of_day'] == 'Today' )
								{
									mainthis.mainModel['todayAppointment'] = true;
								}
						});
					}
				
				let vitals_per = Math.round(((data['tasks_done']['Vitals']/data['today_task_number']['Vitals'])*100) / data['days_count']);
				let activity_per = Math.round(((data['tasks_done']['Activity']/data['today_task_number']['Activity'])*100)/ data['days_count']);
				let diet_per = Math.round(((data['tasks_done']['Diet']/data['today_task_number']['Diet'])*100)/ data['days_count']);
				let medication_per = Math.round(((data['tasks_done']['Medications']/data['today_task_number']['Medications'])*100)/ data['days_count']);
				let questions_per = Math.round(((data['tasks_done']['Questions']/data['today_task_number']['Questions'])*100)/ data['days_count']);
				let all_per = Math.round(((data['tasks_done']['All']/data['today_task_number']['All'])*100)/ data['days_count']);
				window['task_percentage'] = {
												'Vitals': isFinite(vitals_per) ? vitals_per : 0,
												'Activity': isFinite(activity_per) ? activity_per : 0,
												'Diet': isFinite(diet_per) ? diet_per : 0,
												'Medications': isFinite(medication_per) ? medication_per : 0,
												'Questions': isFinite(questions_per) ? questions_per : 0,
												'All': isFinite(all_per)? all_per : 0,
											}
											// THIS WAS BEFORE ON ALL PLACES
											 
				mainthis.mainModel['allTasksPercentage'] = window['task_percentage'];
				mainthis.mainModel['daily_tasks_done'] = window['daily_tasks_done'];
				mainthis.mainModel['task_done_percentage'] = window['task_percentage']['All'];
				mainthis.mainModel['activity_percentage'] = window['task_percentage']['Activity'];	
				if ( mainthis.mainModel['daily_tasks_done'].length > 0) { setTimeout( function() { loadCanvas('all_dashboard'); }, 200) }
			});
	}
	
	
function loadCanvas(pg)
	{
		console.log('FROM STATUS IN Function');
		let canvasIdBig = '#health_canvas';
		let canvasIdSmall = '#activity_canvas';
		
		if ( $('dashboard-stats:visible').length > 0 )
			{
				canvasIdBig = '#health_canvas'+'_st';
				canvasIdSmall = '#activity_canvas'+'_st';
			}
		
		let lineWidth1 = $('page-dashboard .'+pg+' .circle_outside').width()/7;
		let lineWidth2 = $('page-dashboard .'+pg+' .circle_outside').width()/9;
		let two_canvas = [['page-dashboard .'+pg+' .circle_outside', 'page-dashboard .'+pg+'  '+canvasIdBig, '#039be5', mainthis.mainModel['task_done_percentage'], lineWidth1], ['page-dashboard .'+pg+'  .inside_circle', 'page-dashboard .'+pg+' '+canvasIdSmall, '#7cd023', mainthis.mainModel['activity_percentage'], lineWidth2]]
		
		if ( $('.all_dashboard:visible').length > 0 || $('.dashboard_stats:visible').length > 0 )
			{
				$.each(two_canvas,function(a, b)
					{
						let circleOutWidth = $(b[0]).width();
						let widthLine = b[4];
						let angle1 = 0;
						let angle2 = b[3]*3.6;
						
						$(b[1]).width(circleOutWidth+widthLine);
						$(b[1]).height(circleOutWidth+widthLine);
						$(b[1]).css({'left': -(widthLine/2)+'px', 'top': -(widthLine/2)+'px'});
						
						let canvas = <HTMLCanvasElement>$(b[1])[0];
						let context = canvas.getContext('2d');
						
						context.canvas.width = $(b[1]).width()*2;
						context.canvas.height = $(b[1]).height()*2;

						context.beginPath();
						context.arc(canvas.width/2+widthLine/8,widthLine,widthLine,0,2*Math.PI);
						context.fillStyle = b[2];
						context.fill();
						
						let x_cordinate = canvas.width/2 + canvas.width/2 * Math.sin(angle2 * (Math.PI / 180)) - widthLine * Math.sin(angle2 * (Math.PI / 180));
						let y_cordinate = canvas.height/2 - canvas.height/2 * Math.cos(angle2 * (Math.PI / 180)) + widthLine * Math.cos(angle2 * (Math.PI / 180)) ;

						context.beginPath();
						context.arc(x_cordinate,y_cordinate,widthLine,0,2*Math.PI);
						context.fillStyle = b[2];
						context.fill();
						
						
						context.beginPath();
						context.arc(canvas.width/2 ,canvas.height/2 ,canvas.width/2-widthLine , (angle2-90) * (Math.PI / 180), -90 * (Math.PI / 180), true);
						context.fillStyle = b[2];
						context.lineWidth = widthLine*2;
						context.strokeStyle = b[2];
						context.stroke();
						
						$(b[0]).find('.absolute_percentage').css({'width': widthLine*2+'px', 'height': widthLine*2+'px', 'left': (canvas.width/4-widthLine-widthLine/8.1)+'px', 'top': (-widthLine + widthLine/25)+'px'})
						
					});
			}
	}