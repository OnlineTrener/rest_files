
	let xDiff = 0;
	let canvasWidth;
	let canvasHeight;
	let numberI = 0;	
	let inside_canvas = 'myhealth';	
	let path;
	let path1;
	function loadCanvas()
		{
			let max_num = maxValue;
			let average_num = 0;
			let average_num1 = 0;
			let average_num_real = 0;
			let average_num_real1 = 0;
			let diffd = endDate - startDate;
			let newdifD = 0;
			
			mainthis.mainModel['canvasValue'] = [];					
			
			let template = '';
			let template_activity = '';
			if ( type_graph == 'Activity' )
				{
					template_activity = '';
					let activity_name = '';
					let numb_c = 1;
					$.each(health_arr, function(c,d) 
						{
							average_num = 0;
							$.each(d['value'], function(ce,de) 
								{
									if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
									average_num = average_num + parseInt(de[0]);
								});
								
							if ( d['name'] == 'Walking (miles)' ) { activity_name = 'miles walked' }	
							else if ( d['name'] == 'Running (miles)' ) { activity_name = 'miles runing' }	
							else if ( d['name'] == 'Duration (minutes)' ) { activity_name = 'minutes duration' }	
								
							let class_width = '';	
							if ( numb_c%2 != 0 ) { class_width = 'width33perl' }
							else { class_width = 'width34perc' }
							
							template_activity += '<div class="'+class_width+'">'+
														'<div class="icon_total"></div>'+	
														'<div class="big_average_txt">'+average_num+'</div>'+	
														'<div class="small_txt width60px">'+activity_name+'</div>'+	
													'</div>';
								
							numb_c++;	
						});
						
					if ( health_arr && health_arr.length > 0 )
						{
							template = '<div class="subtitle_15 pad_tb10">TOTAL</div>'+
										'<div class="total_div">'+
											template_activity+
										'</div>';			
						}
				}
				
			loadX();
					
			let template_date_x = '';
			let template_date_month = '';
			let last_year = 1990;
			$.each(mainthis.mainModel['dateArr'], function(a, b)
				{
					let tme = new Date(b[0]);
					let template_span = '';
					
						
					let months = ["Jan", "Feb", "Mar", "Apr", "May", "Jun", "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"];
					let month_x = months[tme.getMonth()];
					
					let inserted_one = 0;
					
					if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' || mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST TWO WEEK AGO' || mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST MONTH' )
						{	
							if ( a != 0 && (a+1) != mainthis.mainModel['dateArr'].length )
								{
									template_span = '<span>'+tme.getDate()+'</span>';
								}
								
							template_date_x += '<div class="date_part_x" style="width: '+b[1]+'px">'+template_span+'</div>';	
							template_span = '';
							if ( a == 0 )
								{
									template_span = '<span style="padding-left: 10px;">'+month_x+'</span>';
								}
							else if ( ( tme.getDate() == 1 || tme.getDate() == 2 || tme.getDate() == 3 || tme.getDate() == 4 ) && inserted_one == 0  )
								{
									template_span += '<span>'+month_x+'</span>';
									inserted_one = 1;
								}
						}
					else if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST 3 MONTHS' || mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST 6 MONTHS' || mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST YEAR'  )
						{
							if ( a != 0 && (a+1) != mainthis.mainModel['dateArr'].length )
								{
									template_span = '<span>'+month_x+'</span>';
								}
								
							template_date_x += '<div class="date_part_x" style="width: '+b[1]+'px">'+template_span+'</div>';
							template_span = '';
							
							if ( last_year != tme.getFullYear() )
								{
									template_span += '<span>'+tme.getFullYear()+'</span>';
									last_year = tme.getFullYear();
								}
						}									

					
					template_date_month += '<div class="date_part_month" style="width: '+b[1]+'px">'+template_span+'</div>';	
				});	
				
			console.log('health_arr');	
			console.log(health_arr);	
			console.log('health_arr');	
			$.each(health_arr, function(c,d) 
				{
					if ( d['name'] != 'Blood Pressure (mmhg)' && d['name'] != 'Glucose (mg/dl)' )
						{
							if ( d['value'].length > 0 )
								{
									max_num = 0;
									average_num = 0;
									average_num_real = 0;
									$.each(d['value'], function(ce,de) 
										{
											if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
											average_num = average_num + parseInt(de[0]);
										});
									
									average_num_real = Math.round(average_num/d['value'].length);
									
									mainthis.mainModel['canvasValue'] = [];					
									maxValue = max_num + 10;
									let const_left = Math.round((maxValue-minValue)/8);
									for ( let i = 1; i<8; i++ )
										{
											mainthis.mainModel['canvasValue'].push(minValue+const_left*i);
										}
									
									mainthis.mainModel['canvasValue'].reverse();
									
									let template_one_part = '';
									let template_abs_val = '';
									$.each(mainthis.mainModel['canvasValue'], function(a, b)
										{
											template_one_part += '<div class="one_part"></div>';
											template_abs_val += '<div class="one_part1">'+
																	'<div class="abs_number">'+b+'</div>'+
																'</div>';
										});
									
									
									
									
									template += '<div class="outside_graph">'+
														'<div class="graph_health_part" id="rel_100'+c+'">'+
															'<div class="rel_101" id="rel_101'+c+'">'+
																'<canvas class="vitals_canvas" id="health_canvas1'+c+'" width="100" height="100"></canvas>'+
																'<div class="abs_for_circle">'+
																	'<div class="width1per">'+
																	'</div><div class="rest99per txt_center">'+
																		'<div class="big_average_txt">'+average_num_real+'</div>'+
																		'<div class="small_txt">'+d['value'][0][2]+'</div>'+
																	'</div>'+
																'</div>'+
															'</div>	'+
															'<div class="one_part">'+
																'<div class="height100per" >'+
																	'<div class="width1per">'+
																	'</div><div class="rest99per">'+
																		'<div class="font14">'+d['name']+'</div>'+
																	'</div>'+
																'</div>'+
															'</div>'+
															template_one_part+
														'</div>'+
														'<div class="abs_val">'+
															template_abs_val+
														'</div>'+
														'<div class="abs_x_health" style="width: '+abs_x_width+'px">'+
															'<div class="rel_100per">'+ 
																	template_date_x+
																'<div class="abs_date_month">'+
																	template_date_month+
																'</div>'+
																'<div class="abs_grey_line"></div>'+ 
															'</div>'+
														'</div>'+
													'</div>'; 
								}
							
						}
					else
						{
							if ( d['value']['graph1'].length > 0 && d['value']['graph2'].length > 0 )
								{
									max_num = 0;
									average_num = 0;
									average_num1 = 0;
									average_num_real = 0;
									average_num_real1 = 0;
									$.each(d['value']['graph1'], function(ce,de) 
										{
											if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
											average_num = average_num + parseInt(de[0]);
										});
									
									$.each(d['value']['graph2'], function(ce,de) 
										{
											if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
											average_num1 = average_num1 + parseInt(de[0]);
										});
									
									average_num_real = Math.round(average_num/d['value']['graph1'].length);
									average_num_real1 = Math.round(average_num1/d['value']['graph2'].length);
									
									mainthis.mainModel['canvasValue'] = [];					
									maxValue = max_num + 10;
									let const_left = Math.round((maxValue-minValue)/8);
									for ( let i = 1; i<8; i++ )
										{
											mainthis.mainModel['canvasValue'].push(minValue+const_left*i);
										}
									
									mainthis.mainModel['canvasValue'].reverse();
									
									let template_one_part = '';
									let template_abs_val = '';
									$.each(mainthis.mainModel['canvasValue'], function(a, b)
										{
											template_one_part += '<div class="one_part"></div>';
											template_abs_val += '<div class="one_part1">'+
																	'<div class="abs_number">'+b+'</div>'+
																'</div>';
										});
									let name_val1 = 'Diastolic';
									let name_val2 = 'Systolic';
									
									if ( d['name'] == 'Glucose (mg/dl)' )
										{
											name_val1 = 'After Meal';
											name_val2 = 'Before Meal';
										}
								
								
									template += '<div class="outside_graph">'+
														'<div class="graph_health_part" id="rel_100'+c+'">'+
															'<div class="rel_101" id="rel_101'+c+'">'+
																'<canvas class="vitals_canvas" id="health_canvas1'+c+'" width="100" height="100"></canvas>'+
																'<div class="abs_for_circle">'+
																	'<div class="width1per">'+
																	'</div><div class="rest99per txt_center">'+
																		'<div class="big_average_txt">'+average_num_real1+'</div>'+
																		'<div class="small_txt">'+name_val1+'</div>'+
																	'</div>'+
																'</div>'+
																'<div class="abs_for_circle1">'+
																	'<div class="width1per">'+
																	'</div><div class="rest99per txt_center">'+
																		'<div class="big_average_txt">'+average_num_real+'</div>'+
																		'<div class="small_txt">'+name_val2+'</div>'+
																	'</div>'+
																'</div>'+
															'</div>	'+
															'<div class="one_part">'+
																'<div class="height100per" >'+
																	'<div class="width1per">'+
																	'</div><div class="rest99per">'+
																		'<div class="font14">'+d['name']+'</div>'+
																	'</div>'+
																'</div>'+
															'</div>'+
															template_one_part+
														'</div>'+
														'<div class="abs_val">'+
															template_abs_val+
														'</div>'+
														'<div class="abs_x_health" style="width: '+abs_x_width+'px">'+
															'<div class="rel_100per">'+ 
																	template_date_x+
																'<div class="abs_date_month">'+
																	template_date_month+
																'</div>'+
																'<div class="abs_grey_line"></div>'+ 
															'</div>'+
														'</div>'+
													'</div>'; 
								}
						}	
													
				});
			
			$('#vitals_graph').html(template);
			
			if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' || mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST TWO WEEK AGO' || mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST MONTH' )
				{
					$('.abs_date_month').css({'left': '-11px'});
				}
			else if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST 3 MONTHS' || mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST 6 MONTHS' || mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST YEAR'  )
				{
					$('.abs_date_month').css({'left': '-6px'});
				}				
			
			template = '';
			$.each(health_arr, function(c,d) 
				{
					if ( d['name'] != 'Blood Pressure (mmhg)' && d['name'] != 'Glucose (mg/dl)' )
						{
							if ( d['value'].length > 1 )
								{
									let canvas = <HTMLCanvasElement>$('page-'+inside_canvas+' #health_canvas1'+c+'')[0];
									let context = canvas.getContext('2d');
									
									context.canvas.width = $('page-'+inside_canvas+' #health_canvas1'+c+'').width()*2;
									context.canvas.height = $('page-'+inside_canvas+' #health_canvas1'+c+'').height()*2;

									let prevheartx = 0;
									let prevhearty = 0;
									let first_prevhartx = 0;
									let first_prevharty = 0;
									let line_width = 0;
									
									let heartx = 0;
									let hearty = 0;
									prevheartx = 0;
									prevhearty = 0;
									
									max_num = 0;
									
									path = new Path2D();
									
									$.each(d['value'], function(ce,de) 
										{
											if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
										});	
										
									$.each(d['value'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;

											
											if ( first_prevhartx == 0 ) { first_prevhartx = prevheartx; }
											if ( first_prevharty == 0 ) { first_prevharty = prevhearty; }
											
											if ( ce == 0  && prevheartx == 0) 
												{	
													path.moveTo(heartx, hearty); 
												}
											if ( ce != 0 ) 
												{
													context.fillStyle = 'rgba(0, 188, 212, 0.2)';
													if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' ) { path.lineTo(heartx, hearty); }
													else { path.bezierCurveTo(prevheartx+20, prevhearty-5, heartx-20,hearty-5,heartx,hearty); }
												}
												
											prevheartx = heartx;
											prevhearty = hearty;
										});
										
									path.lineTo(heartx, canvas.height);
									path.lineTo(first_prevhartx, canvas.height);
									path.lineTo(first_prevhartx, first_prevharty);
									context.fill(path);
									
									prevheartx = 0;
									prevhearty = 0;
								
									$.each(d['value'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;

											if ( ce != 0 ) 
												{
													 if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' ) 
														{ 
															line_width = ((((Math.abs(heartx-prevheartx))/(Math.abs(hearty-prevhearty)))/7)+0.5)*15;
															context.beginPath();
															
															context.moveTo(prevheartx, prevhearty+line_width/2); 
															context.lineTo(heartx, hearty+line_width/2);
															
															if ( line_width > 10 ) { line_width = 10; }
														
															context.strokeStyle = 'rgba(105, 191, 202, 0.1)';
															context.lineWidth = line_width;
															context.stroke();
														}
													else
														{
															line_width = ((((Math.abs(heartx-prevheartx))/(Math.abs(hearty-prevhearty)))/7)+0.5)*18;
															context.beginPath();
															
															context.moveTo(prevheartx, prevhearty+line_width/2);
															context.bezierCurveTo(prevheartx+20, prevhearty-5, heartx-20,hearty-5,heartx,hearty+line_width/2);
															
															context.strokeStyle = 'rgba(105, 191, 202, 0.1)';
															context.lineWidth = line_width;
															context.stroke();
														}												
												}
												
											prevheartx = heartx;
											prevhearty = hearty;
											 if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' )
												{ 
													context.beginPath();
													context.moveTo(heartx, hearty);
													context.lineTo((heartx), (canvas.height));
													context.strokeStyle = 'rgba(198,201,203,0.5)';
													context.lineWidth = 1;
													context.stroke();
												}
											if ( ce == (d['value'].length-1) )
												{
													prevheartx = 0;
													prevhearty = 0;
												}
										});	
									
									$.each(d['value'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;
											
											if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' )
												{
													context.beginPath();
													context.arc(heartx,hearty,10,0,2*Math.PI);
													context.strokeStyle = '#00bcd4';
													context.fillStyle = '#00bcd4';
													context.fill();
													context.stroke(); 
												}
										});
								}
						}
					else
						{
							if ( d['value']['graph1'].length > 1 && d['value']['graph2'].length > 1 )
								{
									let canvas = <HTMLCanvasElement>$('page-'+inside_canvas+' #health_canvas1'+c+'')[0];
									let context = canvas.getContext('2d');
									
									context.canvas.width = $('page-'+inside_canvas+' #health_canvas1'+c+'').width()*2;
									context.canvas.height = $('page-'+inside_canvas+' #health_canvas1'+c+'').height()*2;

									let prevheartx = 0;
									let prevhearty = 0;
									let first_prevhartx = 0;
									let first_prevharty = 0;
									let line_width = 0;
									
									let heartx = 0;
									let hearty = 0;
									prevheartx = 0;
									prevhearty = 0;
									
									max_num = 0;
									
									path1 = new Path2D();
									
									$.each(d['value']['graph1'], function(ce,de) 
										{
											if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
										});	
									
									$.each(d['value']['graph2'], function(ce,de) 
										{
											if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
										});	
										
									$.each(d['value']['graph2'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;

											
											if ( first_prevhartx == 0 ) { first_prevhartx = prevheartx; }
											if ( first_prevharty == 0 ) { first_prevharty = prevhearty; }
											
											if ( ce == 0  && prevheartx == 0) 
												{	
													path1.moveTo(heartx, hearty); 
												}
											if ( ce != 0 ) 
												{
													context.fillStyle = 'rgba(0, 188, 212, 0.2)';
													if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' ) { path1.lineTo(heartx, hearty); }
													else { path1.bezierCurveTo(prevheartx+20, prevhearty-5, heartx-20,hearty-5,heartx,hearty); }
												}
												
											prevheartx = heartx;
											prevhearty = hearty;
										});
										
									path1.lineTo(heartx, canvas.height);
									path1.lineTo(first_prevhartx, canvas.height);
									path1.lineTo(first_prevhartx, first_prevharty);
									
									context.fill(path1);
									
									prevheartx = 0;
									prevhearty = 0;
								
									$.each(d['value']['graph2'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;

											if ( ce != 0 ) 
												{
													if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' ) 
														{ 
															line_width = ((((Math.abs(heartx-prevheartx))/(Math.abs(hearty-prevhearty)))/7)+0.5)*15;
															context.beginPath();
															
															context.moveTo(prevheartx, prevhearty+line_width/2); 
															context.lineTo(heartx, hearty+line_width/2);
															
															if ( line_width > 10 ) { line_width = 10; }
														
															context.strokeStyle = 'rgba(105, 191, 202, 0.1)';
															context.lineWidth = line_width;
															context.stroke();
														}
													else
														{
															line_width = ((((Math.abs(heartx-prevheartx))/(Math.abs(hearty-prevhearty)))/7)+0.5)*18;
															context.beginPath();
															
															context.moveTo(prevheartx, prevhearty+line_width/2);
															context.bezierCurveTo(prevheartx+20, prevhearty-5, heartx-20,hearty-5,heartx,hearty+line_width/2);
															
															context.strokeStyle = 'rgba(105, 191, 202, 0.1)';
															context.lineWidth = line_width;
															context.stroke();
														}
												}
												
											prevheartx = heartx;
											prevhearty = hearty;
											if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' )
												{
													context.beginPath();
													context.moveTo(heartx, hearty);
													context.lineTo((heartx), (canvas.height));
													context.strokeStyle = 'rgba(198,201,203,0.5)';
													context.lineWidth = 1;
													context.stroke();
												}
											if ( ce == (d['value']['graph2'].length-1) )
												{
													prevheartx = 0;
													prevhearty = 0;
												}
											
										});	

									$.each(d['value']['graph2'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;
											if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' )
												{
													context.beginPath();
													context.arc(heartx,hearty,10,0,2*Math.PI);
													context.strokeStyle = '#00bcd4';
													context.fillStyle = '#00bcd4';
													context.fill();
													context.stroke(); 
												}
										});
										
									first_prevhartx = 0;
									first_prevharty = 0;
									line_width = 0;
									
									heartx = 0;
									hearty = 0;
									prevheartx = 0;
									prevhearty = 0;
									
									max_num = 0;
									
									path = new Path2D();
									
									$.each(d['value']['graph1'], function(ce,de) 
										{
											if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
										});	
									
									$.each(d['value']['graph2'], function(ce,de) 
										{
											if ( parseInt(de[0])+10 > max_num ) { max_num = parseInt(de[0])+10; }
										});	
										
									$.each(d['value']['graph1'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;

											
											if ( first_prevhartx == 0 ) { first_prevhartx = prevheartx; }
											if ( first_prevharty == 0 ) { first_prevharty = prevhearty; }
											
											if ( ce == 0  && prevheartx == 0) 
												{	
													path.moveTo(heartx, hearty); 
												}
											if ( ce != 0 ) 
												{
													context.fillStyle = 'rgba(28,138,192,0.2)';
													if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' ) { path.lineTo(heartx, hearty); }
													else { path.bezierCurveTo(prevheartx+20, prevhearty-5, heartx-20,hearty-5,heartx,hearty); }
												}
												
											prevheartx = heartx;
											prevhearty = hearty;
										});
										
									path.lineTo(heartx, canvas.height);
									path.lineTo(first_prevhartx, canvas.height);
									path.lineTo(first_prevhartx, first_prevharty);
									
									context.fill(path);
									
									prevheartx = 0;
									prevhearty = 0;
								
									$.each(d['value']['graph1'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;

											if ( ce != 0 ) 
												{
													if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' ) 
														{ 
															line_width = ((((Math.abs(heartx-prevheartx))/(Math.abs(hearty-prevhearty)))/7)+0.5)*15;
															context.beginPath();
															
															context.moveTo(prevheartx, prevhearty+line_width/2); 
															context.lineTo(heartx, hearty+line_width/2);
															
															if ( line_width > 10 ) { line_width = 10; }
														
															context.strokeStyle = 'rgba(3,155,229,0.1)';
															context.lineWidth = line_width;
															context.stroke();
														}
													else
														{
															line_width = ((((Math.abs(heartx-prevheartx))/(Math.abs(hearty-prevhearty)))/7)+0.5)*18;
															context.beginPath();
															
															context.moveTo(prevheartx, prevhearty+line_width/2);
															context.bezierCurveTo(prevheartx+20, prevhearty-5, heartx-20,hearty-5,heartx,hearty+line_width/2);
															
															context.strokeStyle = 'rgba(3,155,229,0.1)';
															context.lineWidth = line_width;
															context.stroke();
														}
												}
												
											prevheartx = heartx;
											prevhearty = hearty;
											
											if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' )
												{
													context.beginPath();
													context.moveTo(heartx, hearty);
													context.lineTo((heartx), (canvas.height));
													context.strokeStyle = 'rgba(198,201,203,0.5)';
													context.lineWidth = 1;
													context.stroke();
												}
											if ( ce == (d['value']['graph1'].length-1) )
												{
													prevheartx = 0;
													prevhearty = 0;
												}
											
										});	 
										
									$.each(d['value']['graph1'], function(ce,de) 
										{
											let cons = canvas.height/max_num;
											heartx = canvas.width*((de[1]-startDate)/diffd);
											hearty = canvas.height - parseInt(de[0]) * cons;
											
											if ( mainthis.mainModel['date_stats'] == 'SHOWING FOR LAST WEEK' )
												{
													context.beginPath();
													context.arc(heartx,hearty,10,0,2*Math.PI);
													context.strokeStyle = '#039be5';
													context.fillStyle = '#039be5';
													context.fill();
													context.stroke(); 
												}
										});	
								}
						}			
				});
				
		}	