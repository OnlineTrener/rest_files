<?php
	require_once('config.php');
	if ( !$_SESSION['user'] )
		{
			header('Location: /');
			echo '<script type="text/javascript"> window.location = "/"; </script>';
			die();
		}
	if( $_FILES['squere'] )
			{
				
				
				if ($_FILES['squere']['error'] == 0)
					{
						$ext= strtolower(end(explode('.',$_FILES['squere']['name'])));
						if ( $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif' || $ext == 'pdf' || $ext == 'txt' || $ext == 'doc' || $ext == 'docx')
							{
								$newname1 = md5(time()).'.'.$ext;
								move_uploaded_file($_FILES['squere']['tmp_name'], 'uploads/'.$newname1);
								
								$img = '/img/file.png';
								if ( $ext == 'png' || $ext == 'jpg' || $ext == 'jpeg' || $ext == 'gif')
									{
										$img = '/uploads/'.$newname1;
									}
								
								?>
								<script type="text/javascript">
									for ( var i = 1; i <= 6; i++ )
										{
											if ( window.parent.$e('fdiv_'+i).innerHTML == '' )
												{
													window.parent.$e('fdiv_'+i).style.filter = 'progid:DXImageTransform.Microsoft.AlphaImageLoader(src=\'<?php echo $img; ?>\', sizingMethod=\'scale\')';
													window.parent.$e('fdiv_'+i).innerHTML = '<input type="hidden" id="file_up_'+ i +'" name="file_up[]" value="<?php echo $newname1; ?>" />';
													window.parent.$e('fdiv_'+i).style.backgroundImage = 'url(\'<?php echo $img; ?>\')';
													window.parent.$e('fdiv_'+i).style.backgroundSize = 'cover';
												
													i = 10;
													
												}
										
	
										}
								</script>
								<?php
								die();
							}
					}
			}
	
	
	$id = intval($_GET['id']);
	$noid = intval($_GET['n']);
	
	$b = mysqli_query($q, 'SELECT * FROM kids WHERE status = 1 AND id = '.$id.' AND id IN ( SELECT kid_id FROM assigned WHERE user_id = \''.$_SESSION['user']['id'].'\' ) ');
	$r = mysqli_fetch_array($b);
	
	if ( !$r )
		{
			header('Location: /patients'); 
			echo '<script type="text/javascript"> window.location = "/patients"; </script>';
			die();
		}
	
	
		if ( $noid )
			{
				$notId = mysqli_query($q, 'SELECT * FROM notes LEFT JOIN notes_ifsp n_ifs ON n_ifs.note_id = notes.id  WHERE notes.id = \''.$noid.'\' AND notes.ispublic = 0') or die(mysqli_error($q));
				if ( $notIdValue = mysqli_fetch_array($notId) )
					{
						$json_note = json_decode($notIdValue['note_details'], true); 
						$new = $noid;
					}
			}
	else 
		{
			$new = 0;		
		}
	if ( $json_note['time_out'] && $json_note['time_out'] !== '' )
		{
		$h = explode(' ', $json_note['time_out']); 
		$h1 = explode(':',$h[0]);
		}
	if ( $json_note['time_in'] && $json_note['time_in'] !== '' )
		{
		$n1 = explode(' ', $json_note['time_in']); 
		$n11 = explode(':',$n1[0]);
		}
	$mn = ['AM','PM'];
	require_once('header.php'); 
?>
	<div class="under_header_blue">
		<div class="inside_blue">
			<img class="inside_blue_photo" src="/photos/<?php  echo $r['photo']; ?>" alt="" />
			<div class="text_inside_blue">
				<div class="text_inside"><?php  echo $r['first_name'].' '.$r['last_name']; ?></div>
				<div class="text_inside1">Birth: <?php echo date('jS F, Y', $r['date_of_birth']); ?></div>
			</div>
			<div class="text_inside_blue1">Gestational Weeks: <?php  echo $r['gest_weeks'].' weeks'?></div>
		</div>
	</div>
	<div class="huge_div" id="all_edit_note">
	<div class="title20">
		<div class="textin_field">Add Daily Note</div>
	</div>
	<div class="just_two_line"></div>
	<div class="part_div">  
		<div style="margin-bottom: 10px;">
			<div class="daily_text">Date:</div>
			<div class="date_note3"><div class="daily_note_date"  id="cal1" onclick="makeCalendar(this)"><?php if ( $json_note['date_note'] !== '' ) { echo $json_note['date_note']; } ?></div></div>
		</div>
		<div class="daily_text">Time In:</div>
		<div class="date_note3">
		<select class="cal_select" id="from_h">
					<?php
					
					for ( $i = 1; $i < 13; $i++)
						{
					?>
						<option <?php if($n11 && $n11[0] == $i) { echo 'selected='.$n11[0]; } ?>><?php echo $i; ?></option>
					<?php
						}
					?>
					</select>
					<select class="cal_select" id="from_m">
					<?php 
					for ( $i = 0; $i < 60; $i = $i + 5)
						{
							if( $i < 10 )
							{
								$i = '0'.$i;
							}
					?>
						<option <?php if($n11 && $n11[1] == $i ) { echo 'selected='.$n11[1]; }?>> <?php echo $i; ?> </option>
					<?php
						}
					?>
					</select>
					<select class="cal_select" id="from_a">
					<?php 
					for ( $i = 0; $i < count($mn); $i++)
						{
					?>
						<option <?php if($n1[1] && $n1[1] == $mn[$i]) { echo 'selected ='.$n1[1]; } ?>><?php echo $mn[$i]; ?></option>
					<?php
						}
					?>
			</select>
		</div>		
		<div class="daily_text1">Time Out:</div>
		<div class="clock_relat">
		<select class="cal_select" id="end_h">
					<?php
					
					for ( $i = 1; $i < 13; $i++)
						{
					?>
						<option <?php if($h1 && $h1[0] == $i) { echo 'selected='.$h1[0]; } ?>><?php echo $i; ?></option>
					<?php
						}
					?>
					</select>
					<select class="cal_select" id="end_m">
					<?php 
					for ( $i = 0; $i < 60; $i = $i + 5)
						{
							if( $i < 10 )
							{
								$i = '0'.$i;
							}
					?>
						<option <?php if($h1 && $h1[1] == $i ) { echo 'selected='.$h1[1]; }?>> <?php echo $i; ?> </option>
					<?php
						}
					?>
					</select>
					<select class="cal_select" id="end_a">
					<?php 
					for ( $i = 0; $i < count($mn); $i++)
						{
					?>
						<option <?php if($h[1] && $h[1] == $mn[$i]) { echo 'selected ='.$n[1]; } ?>><?php echo $mn[$i]; ?></option>
					<?php
						}
					?>
				</select>
		</div>
	</div>
	<?php 
		$bifsp = mysqli_query($q, 'SELECT * FROM notes_ifsp WHERE note_id = '.$noid.'');
		$bifsp1 = mysqli_query($q, 'SELECT * FROM notes_ifsp WHERE note_id = '.$noid.' ORDER by id ASC');
		$rifsp = mysqli_fetch_array($bifsp);
		$o = 3;
	
	?>
	<div class="title20">
		<div class="textin_field">IFSP Outcomes</div>
	</div>
	<div class="just_two_line"></div>
	<div class="part_div">
		<div class="text_in_div" style="padding-top: 6px; min-width: 199px;">Date IFSP was created:
		</div><div class="inline_ifsp">
			<div style="margin-bottom: 10px; position: relative;">
				<div class="daily_note_date" id="cal2" onclick="makeCalendar(this)"><?php if ( $rifsp['created_time'] ) { echo date('m/d/Y', $rifsp['created_time']); }  ?></div>
			</div>
		</div>
		<div id="ifspadd_div"><?php while ( $rifsp1 = mysqli_fetch_array($bifsp1) ) {  echo '<div class="ifs_margin"><input id="ifsp_goal'.$o.'" type="text" placeholder="Enter IFSP goal" class="enterifsp_daily" value="'.$rifsp1[goal].'"/><div class="daily_text1">Outcome met:</div><div class="din_rel"><div class="daily_note_date" id="cal'.$o.'" onclick="makeCalendar(this)">'.date('m/d/Y', $rifsp1['met_date']).'</div></div></div>'; $o++; } ?></div>
	</div>
	<div style="width: 170px; margin: auto;">
		<input class="blue_botton1" type="submit" value="Add IFSP Goal" style="width: auto;" onclick="addifsp()" />
	</div>
	<script type="text/javascript">
		
			function makeCalendar(objk)
				{
					var cid = objk.id;
					var elms = objk.parentNode.getElementsByTagName('DIV');
					var cali = document.getElementsByTagName('DIV');
					if ( elms.length == 1 || elms.length == 2)
						{
							objk.parentNode.innerHTML = objk.parentNode.innerHTML + '<div class="calendar_calendar '+cid+'" id="calendar_' + cid + '"><div class="calendar_month"><p id="calendar_mont_' + cid + '"></p> <span id="calendar_year_' + cid + '"></span><div class="calendar_left" onclick="month(\'calendar_left_' + cid + '\',\''+cid+'\')" id="calendar_left_' + cid + '"></div><div class="calendar_right" onclick="month(\'calendar_right_' + cid + '\', \''+cid+'\')" id="calendar_right_' + cid + '"></div></div><div class="calendar_days" id="calendar_days_' + cid + '"></div></div>';
						}
					
							month(null, objk.id);
						
					for(var i = 0; i < calie.length; i++)
						{
							if ( cali[i].className.indexOf('calendar_calendar') > -1 )
								{
									cali[i].style.display = "none";
									mond = d.getMonth();
									mon = mond + 1;
									years = d.getFullYear();
								}	
						}
							$e('calendar_' + cid).style.display = 'block';
				}
				
	</script>
	<div class="title20">
		<div class="textin_field">Information</div>
	</div>
	<div class="just_two_line"></div>
	<div class="part_div">
		<div>
			<div class="text_in_div">Caregiver present:</div>  
			<div class="group_checkb" id="caregiver_present">
				<div class="care_padd">
					<div class="check_deevs"><div class="<?php if ( $json_note['presentc_8'] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="presentc_8" onclick="check(this)"></div><div class="after_check">Parent 1</div></div>
					<div class="check_deevs"><div class="<?php if ( $json_note['presentc_7'] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="presentc_7" onclick="check(this)"></div><div class="after_check">Parent 2</div></div>
					<div class="check_deevs"><div class="<?php if ( $json_note['presentc_1'] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="presentc_1" onclick="check(this)"></div><div class="after_check">Mother</div></div>
					<div class="check_deevs"><div class="<?php if ( $json_note['presentc_2'] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="presentc_2" onclick="check(this)"></div><div class="after_check">Father</div></div>
					<div class="check_deevs"><div class="<?php if ( $json_note['presentc_3'] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="presentc_3" onclick="check(this)"></div><div class="after_check">Nanny</div></div>
					<div class="check_deevs"><div class="<?php if ( $json_note['presentc_4'] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="presentc_4" onclick="check(this)"></div><div class="after_check">Nurse</div></div>
					<div class="check_deevs"><div class="<?php if ( $json_note['presentc_5'] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="presentc_5" onclick="check(this)"></div><div class="after_check">Teachers</div></div>
					<div class="check_deevs" style="padding-top: 15px;" ><div class="<?php if ( $json_note['presentc_6'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="presentc_6" onclick="check(this)"></div><div class="after_check">Grandma</div></div>
				</div>
				<div class="check_deevs only_width"> 
					<div style="padding-bottom: 30px;">
						<div class="checkoth"><div class="<?php if ( $json_note['presentc_9'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="presentc_9" onclick="check(this); checkShowInputNew(this,'other_input0');" style="margin-left: 0px"></div><div class="after_check">Other</div></div>
						<div class="div_for_inp blk"><textarea id="other_input0" type="text" class="area_note_work1" style="<?php if ( $json_note['presentc_9'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['presentc_other'] !== '' ) { echo $json_note['presentc_other'] ; }?></textarea></div>
					</div>
				</div>
			</div>
			<div class="text_in_div">Natural learning environment:</div>
			<div class="group_checkb">
				<div class="care_padd">
					<div class="check_deevs"><div id="naturalle_1" class="<?php if ( $json_note['naturalle_1'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Home</div></div>
					<div class="check_deevs"><div id="naturalle_2" class="<?php if ( $json_note['naturalle_2'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Daycare</div></div>
					<div class="check_deevs"><div id="naturalle_3" class="<?php if ( $json_note['naturalle_3'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Park</div></div>
					<div class="check_deevs"><div id="naturalle_4" class="<?php if ( $json_note['naturalle_4'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Library</div></div>
					<div class="check_deevs"><div id="naturalle_5"class="<?php if ( $json_note['naturalle_5'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Store</div></div>
					<div class="check_deevs">
						<div class="<?php if ( $json_note['naturalle_6'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="naturalle_6" onclick="check(this)"></div><div class="after_check">Playground</div>
					</div>
				</div>
				<div class="check_deevs only_width">
					<div style="padding-bottom: 30px;">
						<div class="checkoth"><div id="naturalle_7" class="<?php if ( $json_note['naturalle_7'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this); checkShowInputNew(this,'other_input1');" style="margin-left: 0px"></div><div class="after_check">Other</div></div>
						<div class="div_for_inp blk"><textarea id="other_input1" type="text" class="area_note_work1" style="<?php if ( $json_note['naturalle_7'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['naturalle_other'] !== '' ) { echo $json_note['naturalle_other'] ; }?></textarea></div>
					</div>
				</div>
			</div>
		</div>
		<div>
			<div class="text_in_div" style="padding-top: 0px;">Previous plan created by caregiver and therapist to practice between sessions:</div>
			<div class="group_checkb" style="padding-top: 5px;">
				<div class="notes_from_lastseasson1">
					<div class="notes_from_lastseasson" id="last_note1" onclick="dropdown(5)"><?php if ( $json_note['previous_caregiver_dropDown'] && $json_note['previous_caregiver_dropDown'] !== '' ) { echo $json_note['previous_caregiver_dropDown']; } else { echo 'Note from last session'; } ?></div> 
					<div class="drop_active" id="drop5" style="display: none;" onmouseout="isin['drop5']=false" onmouseover="isin['drop5']=true">
						<div class="dropdown_div" onclick="choseLastNote(this, 1, 0)">No notes</div>
						<?php
							$ba = mysqli_query($q, 'SELECT * FROM notes WHERE kid_id = '.$id.' ');
							while ( $ba1 = mysqli_fetch_array($ba) )
								{
									$json_ba = json_decode($ba1['note_details'], true);
									if ( $json_ba['previus_cargiveplan_area1'] !== '' )
										{
											 echo '<div class="dropdown_div" onclick="choseLastNote(this, 1, '.$ba1['id'].')">'.$json_ba['previus_cargiveplan_area1'].'</div>';
										}
								}
						?>  
					</div>
				</div>
				<div class="check_deevs"><div class="<?php if ( $json_note['prev_car_plan1'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="prev_car_plan1" onclick="onlyOneCheck(this)"></div><div class="after_check">It worked</div></div>
				<div class="check_deevs"><div class="<?php if ( $json_note['prev_car_plan2'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="prev_car_plan2" onclick="onlyOneCheck(this)"></div><div class="after_check">Didn't work</div></div>
				<div class="check_deevs"><div class="<?php if ( $json_note['prev_car_plan3'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="prev_car_plan3" onclick="onlyOneCheck(this)"></div><div class="after_check">Didn't practice</div></div>
			</div>
		</div>
		<div style="padding-top: 41px;">
			<div class="text_in_div" style="padding-top: 10px;">Previous joint plan:</div>
			<div class="group_checkb" style="padding-top: 5px; " id="forbox">
				<div class="notes_from_lastseasson1">
					<div class="notes_from_lastseasson" id="last_note2" onclick="dropdown(6)"><?php if ( $json_note['previous_join_dropdown'] && $json_note['previous_join_dropdown'] !== '' ) { echo $json_note['previous_join_dropdown'] ; } else { echo 'Note from last session'; } ?></div>
					<div class="drop_active" id="drop6" style="display: none" onmouseout="isin['drop6']=false" onmouseover="isin['drop6']=true">
						<div class="dropdown_div" onclick="choseLastNote(this, 2)">No notes</div>
						<?php
							$bb = mysqli_query($q, 'SELECT * FROM notes WHERE kid_id = '.$id.' ');
							while ( $bb1 = mysqli_fetch_array($bb) )
								{
									$json_bb = json_decode($bb1['note_details'], true); 
									if ( $json_bb['prev_joint_plan_area2'] !== '' )
										{
											 echo '<div class="dropdown_div" onclick="choseLastNote(this, 2, '.$bb1['id'].')">'.$json_bb['prev_joint_plan_area2'].'</div>';
										}
								}
						?>
					</div>
				</div>
				<div class="check_deevs">
					<div class="<?php if ( $json_note['prev_joint_plan1'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="prev_joint_plan1" onclick="onlyOneCheck(this); addArea()"></div>
					<div class="after_check">Practice it today</div>
				</div>
				<div class="check_deevs" style="padding-right: 0px;">
					<div id="prev_joint_plan2" class="<?php if ( $json_note['prev_joint_plan2'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="onlyOneCheck(this); addArea()"></div>
					<div class="after_check" >No, what would you like to practice?</div>
				</div>
				<div style="padding-top: 15px; "><textarea id="area_new1" class="area_note_work1" style="<?php if ( $json_note['prev_joint_plan2'] == '1' ) { echo 'display: block'; } else { echo 'display: none'; } ?>"><?php if ( $json_note['new_box'] && $json_note['new_box'] !==''  ) { echo $json_note['new_box']; } ?></textarea></div>
			</div>
		</div>
		<div style="padding-top: 41px;">
			<div class="text_in_div" style="padding-top: 0px;">Child's state:</div>
			<div class="group_checkb">
				<div class="care_padd">
					<div class="check_deevs">
						<div id="child_state1" class="<?php if ( $json_note['child_state1'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div>
						<div class="after_check">Happy</div>
					</div>
					<div class="check_deevs"><div id="child_state2" class="<?php if ( $json_note['child_state2'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Friendly</div></div>
					<div class="check_deevs"><div id="child_state3" class="<?php if ( $json_note['child_state3'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Talkative</div></div>
					<div class="check_deevs"><div id="child_state4" class="<?php if ( $json_note['child_state4'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Fussy</div></div>
					<div class="check_deevs"><div id="child_state5" class="<?php if ( $json_note['child_state5'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Sleepy</div></div>
				</div>
				<div class="div_f_oth">
					<div class="checkoth">
						<div class="<?php if ( $json_note['child_state6'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="child_state6" onclick="check(this); checkShowInputNew(this,'other_input2');" ></div>
						<div class="after_check">Other</div>
					</div>	
					<div class="div_for_inp blk"><textarea id="other_input2" type="text" class="area_note_work1" style="<?php if ( $json_note['child_state6'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['childsta_other'] !== '' ) { echo $json_note['childsta_other'] ; }?></textarea></div>
				</div>
			</div>
			<div style="padding-top: 18px;">
				<div class="text_in_div" style="padding-top: 0px;">Daily routines activities practiced at today’s session:</div>
				<div class="group_checkb">
					<div class="ar_chec_k">
						<div class="check_deevs"><div id="daily_rou1" class="<?php if ( $json_note['daily_rou1'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Bathing</div></div>
						<div class="check_deevs"><div id="daily_rou2" class="<?php if ( $json_note['daily_rou2'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Naptime</div></div>
						<div class="check_deevs"><div id="daily_rou3" class="<?php if ( $json_note['daily_rou3'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Diapering/toileting</div></div>
						<div class="check_deevs"><div id="daily_rou4" class="<?php if ( $json_note['daily_rou4'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Dressing</div></div>
						<div class="check_deevs"><div id="daily_rou5" class="<?php if ( $json_note['daily_rou5'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Going out</div></div>
						<div class="check_deevs_with_padding_bottom"><div id="daily_rou6" class="<?php if ( $json_note['daily_rou6'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Household tasks</div></div>
						<div class="check_deevs"><div id="daily_rou7" class="<?php if ( $json_note['daily_rou7'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Mealtime</div></div>
						<div class="check_deevs"><div id="daily_rou7" class="<?php if ( $json_note['daily_rou7'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Group play</div></div>
						<div class="check_deevs"><div id="daily_rou7" class="<?php if ( $json_note['daily_rou7'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Classroom activities</div></div>
						<div class="check_deevs"><div id="daily_rou8" class="<?php if ( $json_note['daily_rou8'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Playtime</div></div>
						<div class="check_deevs"><div id="daily_rou9" class="<?php if ( $json_note['daily_rou9'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Playground</div></div>
						<div class="check_deevs_with_padding_bottom"><div id="daily_rou10" class="<?php if ( $json_note['daily_rou10'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" onclick="check(this)"></div><div class="after_check">Reading</div></div>
						<div class="check_deevs_with_padding_bottom">	
							<div class="<?php if ( $json_note['daily_rou1'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="daily_rou1" onclick="check(this)"></div><div class="after_check">Shopping</div>
						</div>
					</div>
					<div class="div_f_oth">
						<div class="checkoth">	
							<div class="<?php if ( $json_note['daily_rou11'] == '1' ) { echo 'checkbox_checked' ; } else { echo 'checkbox'; } ?>" id="daily_rou11" onclick="check(this); checkShowInputNew(this,'other_input3');" ></div>
							<div class="after_check">Other</div>
						</div>
						<div class="div_for_inp blk"><textarea id="other_input3" type="text" class="area_note_work1" style="<?php if ( $json_note['daily_rou11'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['dailyrou_other'] !== '' ) { echo $json_note['dailyrou_other'] ; }?></textarea></div>
					</div>
				</div>
			</div>
		</div>
		<div class="title20">
			<div class="textin_field">Skills Practiced/Learning Opportunities</div>
		</div>
		<div class="just_two_line"></div>
		<div class="sec_part_div">
			<div class="categories_drop_down" id="section_name1" onclick="toggleSection(1)"><span style="font-family: Helvetica4;">Fine Motor Skills</span><div class="absolute_selected">Selected:<span class="selected_skills" id="skills1">
			<?php 
				$skills = array('Bring hands to midline', 'Move each hand', 'Transfer items between hands', 'Hold items with each hand', 'Reach/touch items', 'Reach across body', 'Pick up hand size items', 'Hold cylindrical items', 'Pick up pea size items', 'Align items', 'Stack items', 'Release items', 'Wrist rotation', 'Connect/disconnect toys', 'Fit items',
								'Use index finger', 'Use hand', 'Hold/orient book', 'Turn pages', 'Scribbles', 'Make circles/lines', 'Copy shapes', 'Draw figures', 'Copy/write letters', 'Write name', 'Other');
				$drop_skills = array('Independently', 'Hand over hand assistance', 'Min assistance', 'Mod assistance', 'Max assistance', 'Close supervision', 'Supervision', 'Verbal cues', 'Visual cues', 'Verbal/visual cues', 'Tactile cues', 'Modeling','Other');
				$a = 0;
				for ( $b = 0; $b < count($skills); $b++ )
					{
						if ( $json_note['motskill_fine'.$b] == '1' )
							{
								$a = $a + 1;
							}
					}
				if ( $a > 0 ) { echo $a.' skills'; } else { echo '0 skills'; }  
				
			?>		
			</span></div></div>
			<div class="open_dropdown" id="section1"  style="display: none" >
				<?php
					$ca1 = 0;
					for ( $i=0 ; $i < count($skills); $i++ )
						{
							if ( $i == count($skills)-1 ) { $cbx_class = 'wdth700 wdthPr'; } else { $cbx_class = 'checktxt'; }
							
				?> 
				<div class="dropdown_row">
					<div class="for_removeBor">  
						<div class="<?php echo $cbx_class; ?>">
							<div class="<?php if ( $json_note['motskill_fine'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="motskill_fine<?php echo $i;?>" onclick="check1(this, 1, <?php echo (100+$i)?>); <?php if ( $i == 25 ) { echo 'checkShowInputNew(this,\'dropdw_input1\')'; } ?>"></div> 
							<div class="<?php if ( $i == 25 ) { echo 'last_check'; } else { echo 'after_check1'; } ?>"><?php echo $skills[$i]; ?></div>
							<?php if ( $i == 25 ) { ?><div class="div_for_inp blk"><textarea id="dropdw_input1" type="text" class="area_note_work1 wdthAndMargin" style="<?php if ( $i == 25 && $json_note['motskill_fine25'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['msfine_other'] !== '' ) { echo $json_note['msfine_other'] ; }?></textarea></div><?php } ?>
						</div>
						<?php if ( $i < 25 ) { ?>
						<div class="dropright">
							<div class="innerdrop" id="skills<?php echo (100+$i); ?>" onclick="dropdown(<?php echo (100+$i); ?>)" data-valsel="<?php echo intval($json_note['skills'.(100 + $i)]); ?>">
							<?php  
								$aa1 = 0;
								
								for ( $ba1 = 0; $ba1 < count($drop_skills); $ba1++  )
									{
										if ( $json_note['assist_'.(100+$i).'_'.$ba1] == '1' )
										{
											$aa1 = $aa1 + 1;
										}
									}
								$ca1 += 100; 
								if ( $aa1 > 0 ) { echo 'Selected:<span class="selected_skills" id="span'.(100+$i).'">'.$aa1.' items</span>'; } else { echo 'Independently'; } 				
							?>

							</div> 
							<div class="drop_active" id="drop<?php echo (100+$i); ?>" style="display: none" onmouseout="isin['drop<?php echo (100+$i); ?>']=false" onmouseover="isin['drop<?php echo (100+$i); ?>']=true">
								<?php
									for ( $a = 0; $a< count($drop_skills); $a++ ) 
										{	
											$aee = 0;
											$numCbx = $a+($i*100);
											if ( $a == count($drop_skills)-1 ) { $nuu = '1'; } else { $nuu = '0'; }
											if ( $json_note['assist_'.(100+$i).'_'.$a] && $json_note['assist_'.(100+$i).'_'.$a] == '1' ) { $cbxSql = 'checkbox_checked'; $aee += 1; $span1 = 'Selected:<span class="selected_skills" id="span'.(100+$i).'">'+$aee+'</span>';  } else { $cbxSql = 'checkbox'; }
											if ( $a !== 0 ) { $cbx_and_div = '<div id="assist_'.(100+$i).'_'.$a.'" class="'.$cbxSql.'" onclick="opnOther(this, divFInput'.$i.', '.$nuu.') "></div>'; } else { $cbx_and_div = '<div class="withoutCbx"></div>'; }	
											echo '<div class="cbxAndAssistance" onclick="choseSkillsNew(this, '.(100+$i).', '.$a.','.$numCbx.', 1, \'motskill_fine'.$i.'\')">'.$cbx_and_div.'<div class="dropdown_div dropInline" >'.$drop_skills[$a].'</div></div>';
											if ( $a == count($drop_skills)-1 ) { ?><div class="pad6" id="divFInput_<?php echo (100+$i).'_'.$a.'_1'; ?>" style="<?php if($json_note['assist_'.(100+$i).'_'.$a] && $json_note['assist_'.(100+$i).'_'.$a] == '1' ) { echo 'display: block'; } else { echo 'display: none'; } ?>"><textarea id="assist_<?php echo (100+$i).'_'.$a.'_1'; ?>" data-other="assist_<?php echo (100+$i).'_'.$a; ?>" type="text" class="area_note_work1 wthproc" ><?php if ( $json_note['assist_'.(100+$i).'_'.$a.'_1'] && $json_note['assist_'.(100+$i).'_'.$a.'_1'] !== '' ) { echo $json_note['assist_'.(100+$i).'_'.$a.'_1']; } ?></textarea></div><?php }
										}
								?>
								<div class="div_btnn"><div class="blue_botton1" style="width: 100px; " onclick="backDrop(this, <?php echo (100+$i); ?>);">Done</div></div>  
							</div>
							<div><input type="text" style="display: none;" value="<?php if($json_note['iot1'.$i] || $json_note['iot10'.$i]) { if($i > 9 ) { echo $json_note['iot1'.$i]; } else { echo $json_note['iot10'.$i]; } } ?>" class="input_other_skills" id="iot_<?php echo (100+$i); ?>" /></div>
						</div>
						<?php } ?>
					</div> 
				</div>
				<?php 	 } 		?>
			</div>
			<div class="categories_drop_down" id="section_name2" onclick="toggleSection(2)"><span style="font-family: Helvetica4;">Gross Motor Skills</span><div class="absolute_selected">Selected:<span class="selected_skills" id="skills2">
			<?php
				$agr = 0;
				$skills_gross = array('Move head', 'Move arms', 'Move legs', 'Roll from back to tummy', 'Roll from tummy to back', 'Tummy time/lift head', 'Head control', 'Prop on elbows', 'Prop on hands', 'Pivot on tummy', 'Tummy crawl', 'Hands and knees position', 'Quadruped crawl', 'Sit', 'Sitting reaching/trunk rotation',
									'Transition from sit to tummy and from tummy to sit', 'Get in/out of a chair', 'Pull to kneeling', 'Pull to standing', 'Stand and sit without support', 'Stand with support', 'Stand', 'Cruise', 'Walk with 2 hands', 'Walk with 1 hand', 'Walk independently', 'Pushing toy',
									'Squat', 'Walk fast', 'Run', 'Avoid obstacles', 'Crawl up/down stairs', 'Climb up/down stairs', 'Climb up/down structures', 'Jump', 'Riding/maneuvering riding toy/tricycle', 'Ball activities (roll, kick, throw, catch)', 'Playground activities', 'Go up/down stairs using reciprocal steps', 
									'One foot stance', 'Hop/skip', 'Ride/steer bike', 'Other');
				for ( $b = 0; $b < count($skills_gross); $b++  )
					{
						if ( $json_note['motskill_gross'.$b] == '1' )
						{
							$agr = $agr + 1;
						}
					}
				if ( $agr > 0 ) { echo $agr.' skills'; } else { echo '0 skills'; }
			
			?>
			</span></div></div>
			<div class="open_dropdown" id="section2"  style="display: none" >
				<?php
					$ca2 = 0;
					for ( $i=0; $i < count($skills_gross); $i++ )
						{
							if ( $i == count($skills_gross)-1 ) { $cbx_class1 = 'wdth700 wdthPr'; } else { $cbx_class1 = 'checktxt'; }
							
				?>
				<div class="dropdown_row">
					<div class="for_removeBor"> 
						<div class="<?php echo $cbx_class1; ?>">
							<div class="<?php if ( $json_note['motskill_gross'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="motskill_gross<?php echo $i;?>" onclick="check1(this, 2, <?php echo (200+$i)?>); <?php if ( $i == 42 ) { echo 'checkShowInputNew(this,\'dropdw_input2\')'; } ?>"> </div>
							<div class="<?php if ( $i == 42 ) { echo 'last_check'; } else { echo 'after_check1'; } ?>"><?php echo $skills_gross[$i]; ?></div> 
							<?php if ( $i == 42 ) { ?><div class="div_for_inp blk"><textarea id="dropdw_input2" type="text" class="area_note_work1 wdthAndMargin" style="<?php if ( $i == 42 && $json_note['motskill_gross42'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['msgross_other'] !== '' ) { echo $json_note['msgross_other'] ; }?></textarea></div><?php } ?>
						</div>
						<?php if ( $i < 42 ) { ?>
						<div class="dropright">
							<div class="innerdrop" id="skills<?php echo (200+$i); ?>" onclick="dropdown(<?php echo (200+$i); ?>)"  data-valsel="<?php echo intval($json_note['skills'.(200 + $i)]); ?>">
							<?php  
								$aa2 = 0;
								
								for ( $ba2 = 0; $ba2 < count($drop_skills); $ba2++  )
									{
										if ( $json_note['assist_'.(200+$i).'_'.$ba2] == '1' )
										{
											$aa2 = $aa2 + 1;
										}
									}
								$ca2 += 100; 
								if ( $aa2 > 0 ) { echo 'Selected:<span class="selected_skills" id="span'.(200+$i).'">'.$aa2.' items</span>'; } else { echo 'Independently'; } 				
							?>
							</div> 
							<div class="drop_active" id="drop<?php echo (200+$i); ?>" style="display: none" onmouseout="isin['drop<?php echo (200+$i); ?>']=false" onmouseover="isin['drop<?php echo (200+$i); ?>']=true">
								<?php
									for ( $a = 0; $a< count($drop_skills); $a++ ) 
										{	
											$aee = 0;
											$numCbx = $a+($i*100);
											if ( $a == count($drop_skills)-1 ) { $nuu = '1'; } else { $nuu = '0'; }
											if ( $json_note['assist_'.(200+$i).'_'.$a] && $json_note['assist_'.(200+$i).'_'.$a] == '1' ) { $cbxSql = 'checkbox_checked'; $aee += 1; $span1 = 'Selected:<span class="selected_skills" id="span'.(200+$i).'">'+$aee+'</span>';  } else { $cbxSql = 'checkbox'; }
											if ( $a !== 0 ) { $cbx_and_div = '<div id="assist_'.(200+$i).'_'.$a.'" class="'.$cbxSql.'" onclick="opnOther(this, divFInput'.$i.', '.$nuu.') "></div>'; } else { $cbx_and_div = '<div class="withoutCbx"></div>'; }	
											echo '<div class="cbxAndAssistance" onclick="choseSkillsNew(this, '.(200+$i).', '.$a.','.$numCbx.', 2, \'motskill_gross'.$i.'\')">'.$cbx_and_div.'<div class="dropdown_div dropInline" >'.$drop_skills[$a].'</div></div>';
											if ( $a == count($drop_skills)-1 ) { ?><div class="pad6" id="divFInput_<?php echo (200+$i).'_'.$a.'_1'; ?>" style="<?php if($json_note['assist_'.(200+$i).'_'.$a] && $json_note['assist_'.(200+$i).'_'.$a] == '1' ) { echo 'display: block'; } else { echo 'display: none'; } ?>"><textarea id="assist_<?php echo (200+$i).'_'.$a.'_1'; ?>" data-other="assist_<?php echo (200+$i).'_'.$a; ?>" type="text" class="area_note_work1 wthproc" ><?php if ( $json_note['assist_'.(200+$i).'_'.$a.'_1'] && $json_note['assist_'.(200+$i).'_'.$a.'_1'] !== '' ) { echo $json_note['assist_'.(200+$i).'_'.$a.'_1']; } ?></textarea></div><?php }
										}
								?>
								<div class="div_btnn"><div class="blue_botton1" style="width: 100px; " onclick="backDrop(this, <?php echo (200+$i); ?>);">Done</div></div>
							</div>
							<div><input type="text" style="display: none;" value="<?php if($json_note['iot2'.$i] || $json_note['iot20'.$i]) { if($i > 9 ) { echo $json_note['iot2'.$i]; } else { echo $json_note['iot20'.$i]; } } ?>" class="input_other_skills" id="iot_<?php echo (200+$i); ?>" /></div>
						</div>
						<?php 	 } 		?>
					</div> 
				</div>
				<?php 	 } 		?>
			</div>
			<div class="categories_drop_down" id="section_name3" onclick="toggleSection(3)"><span style="font-family: Helvetica4;">Adaptive Skills</span><div class="absolute_selected">Selected:<span class="selected_skills" id="skills3">
			<?php
				$adas = 0;
				$adaskills = array('Use lips during drinking and eating', 'Swallow liquid', 'Swallow semi-solid', 'Swallow solid', 'Munch/bite and chew', 'Drink from cup', 'Accept food', 'Eat with finger', 'Eat with utensils', 'Pour food/liquid between containers', 'Potty training', 'Hand wash', 'Teeth brushing', 'Dress/undress', 'Eat/drink variety of food with utensil',
									'Serve food', 'Pour liquid', 'Spread/prepare/serve food', 'Use toilet', 'Perform all toileting skills', 'Wash/groom', 'Fasten/unfasten', 'Dress appropriately', 'Other');
				for ( $b = 0; $b < count($adaskills); $b++  )
					{
						if ( $json_note['addaptive_skill'.$b] == '1' )
						{
							$adas = $adas + 1;
						}
					}
				if ( $adas > 0 ) { echo $adas.' skills'; } else { echo '0 skills'; }
			
			?>
			</span></div></div>
			<div class="open_dropdown" id="section3"  style="display: none" >
				<?php
				
					$ca3 = 0;
					for ( $i=0; $i < count($adaskills); $i++ )
						{
							if ( $i == count($adaskills)-1 ) { $cbx_class2 = 'wdth700 wdthPr'; } else { $cbx_class2 = 'checktxt'; }
				?>
				<div class="dropdown_row">
					<div class="for_removeBor"> 
						<div class="<?php echo $cbx_class2; ?>">
							<div class="<?php if ( $json_note['addaptive_skill'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="addaptive_skill<?php echo $i;?>" onclick="check1(this, 3, <?php echo (300+$i); ?>); <?php if ( $i == 23 ) { echo 'checkShowInputNew(this,\'dropdw_input3\')'; } ?>"> 
							</div><div class="<?php if ( $i == 23 ) { echo 'last_check'; } else { echo 'after_check1'; } ?>"><?php echo $adaskills[$i]; ?></div> 
							<?php if ( $i == 23 ) { ?><div class="div_for_inp blk"><textarea id="dropdw_input3" type="text" class="area_note_work1 wdthAndMargin" style="<?php if ( $i == 23 && $json_note['addaptive_skill23'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['adskills_other'] !== '' ) { echo $json_note['adskills_other'] ; }?></textarea></div><?php } ?>
						</div><?php if ( $i < 23 ) { ?><div class="dropright">
							<div class="innerdrop" id="skills<?php echo (300+$i); ?>" onclick="dropdown(<?php echo (300+$i); ?>)"  data-valsel = "<?php echo intval($json_note['skills'.(300 + $i)]); ?>">
							<?php  
								$aa3 = 0;
								
								for ( $ba3 = 0; $ba3 < count($drop_skills); $ba3++  )
									{
										if ( $json_note['assist_'.(300+$i).'_'.$ba3] == '1' )
										{
											$aa3 = $aa3 + 1;
										}
									}
								$ca3 += 100; 
								if ( $aa3 > 0 ) { echo 'Selected:<span class="selected_skills" id="span'.(300+$i).'">'.$aa3.' items</span>'; } else { echo 'Independently'; } 				
							?>
							</div> 
							<div class="drop_active" id="drop<?php echo (300+$i); ?>" style="display: none" onmouseout="isin['drop<?php echo (300+$i); ?>']=false" onmouseover="isin['drop<?php echo (300+$i); ?>']=true">
								<?php
									for ( $a = 0; $a< count($drop_skills); $a++ ) 
										{	
											$aee = 0;
											$numCbx = $a+($i*100);
											if ( $a == count($drop_skills)-1 ) { $nuu = '1'; } else { $nuu = '0'; }
											if ( $json_note['assist_'.(300+$i).'_'.$a] && $json_note['assist_'.(300+$i).'_'.$a] == '1' ) { $cbxSql = 'checkbox_checked'; $aee += 1; $span1 = 'Selected:<span class="selected_skills" id="span'.(300+$i).'">'+$aee+'</span>';  } else { $cbxSql = 'checkbox'; }
											if ( $a !== 0 ) { $cbx_and_div = '<div id="assist_'.(300+$i).'_'.$a.'" class="'.$cbxSql.'" onclick="opnOther(this, divFInput'.$i.', '.$nuu.') "></div>'; } else { $cbx_and_div = '<div class="withoutCbx"></div>'; }	
											echo '<div class="cbxAndAssistance" onclick="choseSkillsNew(this, '.(300+$i).', '.$a.','.$numCbx.', 3, \'addaptive_skill'.$i.'\')">'.$cbx_and_div.'<div class="dropdown_div dropInline" >'.$drop_skills[$a].'</div></div>';
											if ( $a == count($drop_skills)-1 ) { ?><div class="pad6" id="divFInput_<?php echo (300+$i).'_'.$a.'_1'; ?>" style="<?php if($json_note['assist_'.(300+$i).'_'.$a] && $json_note['assist_'.(300+$i).'_'.$a] == '1' ) { echo 'display: block'; } else { echo 'display: none'; } ?>"><textarea id="assist_<?php echo (300+$i).'_'.$a.'_1'; ?>" data-other="assist_<?php echo (300+$i).'_'.$a; ?>" type="text" class="area_note_work1 wthproc" ><?php if ( $json_note['assist_'.(300+$i).'_'.$a.'_1'] && $json_note['assist_'.(300+$i).'_'.$a.'_1'] !== '' ) { echo $json_note['assist_'.(300+$i).'_'.$a.'_1']; } ?></textarea></div><?php }
										}
								?>
								<div class="div_btnn"><div class="blue_botton1" style="width: 100px; " onclick="backDrop(this, <?php echo (300+$i); ?>);">Done</div></div>
							</div>
							<div><input type="text" style="display: none;" value="<?php if($json_note['iot3'.$i] || $json_note['iot30'.$i]) { if($i > 9 ) { echo $json_note['iot3'.$i]; } else { echo $json_note['iot30'.$i]; } } ?>" class="input_other_skills" id="iot_<?php echo (300+$i); ?>" /></div>
						</div>
						<?php 	 } 		?>
					</div> 
				</div>
				<?php 	 } 		?>
			</div>
			<div class="categories_drop_down" id="section_name4" onclick="toggleSection(4)"><span style="font-family: Helvetica4;">Cognitive Skills</span><div class="absolute_selected">Selected:<span class="selected_skills" id="skills7">
			<?php
				$cogs = 0;
				$cogskills = array('Auditory', 'Visual', 'Tactile response', 'Follow object', 'Focus on object', 'Locate hidden items', 'Look/search for items', 'Toy activation', 'Game interaction', 'Imitate action', 'Imitate sounds/words', 'Hold onto item/items', 'Use item to acquire another one', 'Push/pull/go around items', 'Problem solving', 'Representational play', 'Imaginary play', 'Matching items', 'Categorize/group items', 'Sharing', 'Labeling', 'Reading', 'Use opposite concepts', 'Fill with words', 'Know color', 'Shape and size', 'Know quality/quantity', 'Know temporal/special', 'Grouping items', 'Follow 3 steps direction', 'Order item by length/size', 'Retell event', 'Recall event', 'Give solutions', 'Solve problems', 'Imaginary play', 'Accept game rules', 'Counting', 'Know printed numerals', 'Know rhyming', 'Know sound of letters', 'Read', 'Other' );
				for ( $b = 0; $b < count($cogskills); $b++  )
					{
						if ( $json_note['cognitive_skill'.$b] == '1' )
						{
							$cogs = $cogs + 1;
						}
					}
				if ( $cogs ) { echo $cogs.' skills'; } else { echo '0 skills'; }
			
			?>
			</span></div></div>
			<div class="open_dropdown" id="section4"  style="display: none" >
				<?php
					$ca4 = 0;
					for ( $i=0; $i < count($cogskills); $i++ )
						{
							if ( $i == count($cogskills)-1 ) { $cbx_class3 = 'wdth700 wdthPr'; } else { $cbx_class3 = 'checktxt'; }
				?>
				<div class="dropdown_row">
					<div class="for_removeBor"> 
						<div class="<?php echo $cbx_class3; ?>">
							<div class="<?php if ( $json_note['cognitive_skill'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="cognitive_skill<?php echo $i;?>" onclick="check1(this, 7, <?php echo (400+$i); ?>); <?php if ( $i == 42 ) { echo 'checkShowInputNew(this,\'dropdw_input4\')'; } ?>"> 
							</div><div class="<?php if ( $i == 42 ) { echo 'last_check'; } else { echo 'after_check1'; } ?>"><?php echo $cogskills[$i]; ?></div> 
							<?php if ( $i == 42 ) { ?><div class="div_for_inp blk"><textarea id="dropdw_input4" type="text" class="area_note_work1 wdthAndMargin" style="<?php if ( $i == 42 && $json_note['cognitive_skill42'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['cogskills_other'] !== '' ) { echo $json_note['cogskills_other'] ; }?></textarea></div><?php } ?>
						</div><?php if ( $i < 42 ) { ?><div class="dropright">
							<div class="innerdrop" id="skills<?php echo (400+$i); ?>" onclick="dropdown(<?php echo (400+$i); ?>)"  data-valsel = "<?php echo intval($json_note['skills'.(400 + $i)]); ?>">
							<?php  
								$aa4 = 0;
								
								for ( $ba4 = 0; $ba4 < count($drop_skills); $ba4++  )
									{
										if ( $json_note['assist_'.(400+$i).'_'.$ba4] == '1' )
										{
											$aa4 = $aa4 + 1;
										}
									}
								$ca4 += 100; 
								if ( $aa4 > 0 ) { echo 'Selected:<span class="selected_skills" id="span'.(400+$i).'">'.$aa4.' items</span>'; } else { echo 'Independently'; } 				
							?>
							</div> 
							<div class="drop_active" id="drop<?php echo (400+$i); ?>" style="display: none" onmouseout="isin['drop<?php echo (400+$i); ?>']=false" onmouseover="isin['drop<?php echo (400+$i); ?>']=true">
								<?php
									for ( $a = 0; $a< count($drop_skills); $a++ ) 
										{	
											$aee = 0;
											$numCbx = $a+($i*100);
											if ( $a == count($drop_skills)-1 ) { $nuu = '1'; } else { $nuu = '0'; }
											if ( $json_note['assist_'.(400+$i).'_'.$a] && $json_note['assist_'.(400+$i).'_'.$a] == '1' ) { $cbxSql = 'checkbox_checked'; $aee += 1; $span1 = 'Selected:<span class="selected_skills" id="span'.(400+$i).'">'+$aee+'</span>';  } else { $cbxSql = 'checkbox'; }
											if ( $a !== 0 ) { $cbx_and_div = '<div id="assist_'.(400+$i).'_'.$a.'" class="'.$cbxSql.'" onclick="opnOther(this, divFInput'.$i.', '.$nuu.') "></div>'; } else { $cbx_and_div = '<div class="withoutCbx"></div>'; }	
											echo '<div class="cbxAndAssistance" onclick="choseSkillsNew(this, '.(400+$i).', '.$a.','.$numCbx.', 7, \'cognitive_skill'.$i.'\')">'.$cbx_and_div.'<div class="dropdown_div dropInline" >'.$drop_skills[$a].'</div></div>';
											if ( $a == count($drop_skills)-1 ) { ?><div class="pad6" id="divFInput_<?php echo (400+$i).'_'.$a.'_1'; ?>" style="<?php if($json_note['assist_'.(400+$i).'_'.$a] && $json_note['assist_'.(400+$i).'_'.$a] == '1' ) { echo 'display: block'; } else { echo 'display: none'; } ?>"><textarea id="assist_<?php echo (400+$i).'_'.$a.'_1'; ?>" data-other="assist_<?php echo (400+$i).'_'.$a; ?>" type="text" class="area_note_work1 wthproc" ><?php if ( $json_note['assist_'.(400+$i).'_'.$a.'_1'] && $json_note['assist_'.(400+$i).'_'.$a.'_1'] !== '' ) { echo $json_note['assist_'.(400+$i).'_'.$a.'_1']; } ?></textarea></div><?php }
										}
								?>
								<div class="div_btnn"><div class="blue_botton1" style="width: 100px; " onclick="backDrop(this, <?php echo (400+$i); ?>);">Done</div></div>
							</div>
							<div><input type="text" style="display: none;" value="<?php if($json_note['iot4'.$i] || $json_note['iot40'.$i]) { if($i > 9 ) { echo $json_note['iot4'.$i]; } else { echo $json_note['iot40'.$i]; } } ?>" class="input_other_skills" id="iot_<?php echo (400+$i); ?>" /></div>
						</div>
						<?php }  ?>
					</div> 
				</div>
				<?php 	 } 		?>
			</div>
			<div class="categories_drop_down" id="section_name5" onclick="toggleSection(5)"><span style="font-family: Helvetica4;">Social/Communication Skills</span><div class="absolute_selected">Selected:<span class="selected_skills" id="skills4">
			<?php
				$soccs = 0;
				$socskills = array('Awareness of person or sound', 'Joint attention', 'Vocal exchange', 'Pointing', 'Respond/communicate with gestures/sounds/words', 'Greeting', 'Vocalization', 'Word approximation sounds', 'Recognize name', 'Self control/soothe', 'Locate items/people', 'Follow one-step direction', 'Follow two-steps direction', 'Use words to communicate', 'Use signs to communicate', 'Use two-word', 'Use three-word', 'Use sentences to inform various states', 'Take turn talking', 'Use verbs/noun/pronoun/adjective/adverb/articles', 'Ask question', 'Describe', 'Other');
				for ( $b = 0; $b < count($socskills); $b++  )
					{
						if ( $json_note['social_com_skill'.$b] == '1' )
						{
							$soccs = $soccs + 1;
						}
					}
				if ( $soccs ) { echo $soccs.' skills'; } else { echo '0 skills'; }
			?>
			</span></div></div>
			<div class="open_dropdown" id="section5"  style="display: none">
				<?php
					
					$ca5 = 0;
					for ( $i=0; $i < count($socskills); $i++ )
						{
							if ( $i == count($socskills)-1 ) { $cbx_class4 = 'wdth700 wdthPr'; } else { $cbx_class4 = 'checktxt'; }
				?>
				<div class="dropdown_row">
					<div class="for_removeBor"> 
						<div class="<?php echo $cbx_class4; ?>">
							<div class="<?php if ( $json_note['social_com_skill'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="social_com_skill<?php echo $i;?>" onclick="check1(this, 4, <?php echo (500+$i); ?>); <?php if ( $i == 22 ) { echo 'checkShowInputNew(this,\'dropdw_input5\')'; } ?>"> 
							</div><div class="<?php if ( $i == 22 ) { echo 'last_check'; } else { echo 'after_check1'; } ?>"><?php echo $socskills[$i]; ?></div> 
							<?php if ( $i == 22 ) { ?><div class="div_for_inp blk"><textarea id="dropdw_input5" type="text" class="area_note_work1 wdthAndMargin" style="<?php if ( $i == 22 && $json_note['social_com_skill22'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['scomskills_other'] !== '' ) { echo $json_note['scomskills_other'] ; }?></textarea></div><?php } ?>
						</div><?php if ( $i < 22 ) { ?><div class="dropright">
							<div class="innerdrop" id="skills<?php echo (500+$i); ?>" onclick="dropdown(<?php echo (500+$i); ?>)"  data-valsel = "<?php echo intval($json_note['skills'.(500 + $i)]); ?>">
							<?php  
								$aa5 = 0;
								
								for ( $ba5 = 0; $ba5 < count($drop_skills); $ba5++  )
									{
										if ( $json_note['assist_'.(500+$i).'_'.$ba5] == '1' )
										{
											$aa5 = $aa5 + 1;
										}
									}
								$ca5 += 100; 
								if ( $aa5 > 0 ) { echo 'Selected:<span class="selected_skills" id="span'.(500+$i).'">'.$aa5.' items</span>'; } else { echo 'Independently'; } 				
							?>
							</div> 
							<div class="drop_active" id="drop<?php echo (500+$i); ?>" style="display: none" onmouseout="isin['drop<?php echo (500+$i); ?>']=false" onmouseover="isin['drop<?php echo (500+$i); ?>']=true">
								<?php
									for ( $a = 0; $a< count($drop_skills); $a++ ) 
										{	
											$aee = 0;
											$numCbx = $a+($i*100);
											if ( $a == count($drop_skills)-1 ) { $nuu = '1'; } else { $nuu = '0'; }
											if ( $json_note['assist_'.(500+$i).'_'.$a] && $json_note['assist_'.(500+$i).'_'.$a] == '1' ) { $cbxSql = 'checkbox_checked'; $aee += 1; $span1 = 'Selected:<span class="selected_skills" id="span'.(500+$i).'">'+$aee+'</span>';  } else { $cbxSql = 'checkbox'; }
											if ( $a !== 0 ) { $cbx_and_div = '<div id="assist_'.(500+$i).'_'.$a.'" class="'.$cbxSql.'" onclick="opnOther(this, divFInput'.$i.', '.$nuu.') "></div>'; } else { $cbx_and_div = '<div class="withoutCbx"></div>'; }	
											echo '<div class="cbxAndAssistance" onclick="choseSkillsNew(this, '.(500+$i).', '.$a.','.$numCbx.', 4, \'social_com_skill'.$i.'\')">'.$cbx_and_div.'<div class="dropdown_div dropInline" >'.$drop_skills[$a].'</div></div>';
											if ( $a == count($drop_skills)-1 ) { ?><div class="pad6" id="divFInput_<?php echo (500+$i).'_'.$a.'_1'; ?>" style="<?php if($json_note['assist_'.(500+$i).'_'.$a] && $json_note['assist_'.(500+$i).'_'.$a] == '1' ) { echo 'display: block'; } else { echo 'display: none'; } ?>"><textarea id="assist_<?php echo (500+$i).'_'.$a.'_1'; ?>" data-other="assist_<?php echo (500+$i).'_'.$a; ?>" type="text" class="area_note_work1 wthproc" ><?php if ( $json_note['assist_'.(500+$i).'_'.$a.'_1'] && $json_note['assist_'.(500+$i).'_'.$a.'_1'] !== '' ) { echo $json_note['assist_'.(500+$i).'_'.$a.'_1']; } ?></textarea></div><?php }
										}
								?>
								<div class="div_btnn"><div class="blue_botton1" style="width: 100px; " onclick="backDrop(this, <?php echo (500+$i); ?>);">Done</div></div>
							</div>
							<div><input type="text" style="display: none;" value="<?php if($json_note['iot5'.$i] || $json_note['iot50'.$i]) { if($i > 9 ) { echo $json_note['iot5'.$i]; } else { echo $json_note['iot50'.$i]; } } ?>" class="input_other_skills" id="iot_<?php echo (500+$i); ?>" /></div>
						</div>
						<?php }    ?>
					</div> 
				</div>
				<?php 	 } 		?>
			</div>
			<div class="categories_drop_down" id="section_name6" onclick="toggleSection(6)"><span style="font-family: Helvetica4;">Social Skills</span><div class="absolute_selected">Selected:<span class="selected_skills" id="skills5">
			<?php
				$socsk = 0;
				$socskills1 = array('Affection response/display', 'Initiate/response to games/interaction/communication', 'Self-soothe', 'Awareness of external and physical needs', 'Participation in routines', 'Interaction', 'Play with others/with toys', 'Interact', 'Initiate activity', 'Solve conflict', 'Participate on group activity', 'Meet physical needs', 'Follow rules', 'Tell likes/dislikes', 'Know consequences', 'Identify self', 'Other');
				for ( $b = 0; $b < count($socskills1); $b++  )
					{
						if ( $json_note['social_skill'.$b] == '1' )
						{
							$socsk = $socsk + 1;
						}
					}
				if ( $socsk > 0 ) { echo $socsk.' skills'; } else { echo '0 skills'; }
			?>
			</span></div></div>
			<div class="open_dropdown" id="section6"  style="display: none" >
				<?php 
					$ca6 = 0;
					for ( $i=0; $i < count($socskills1); $i++ )
						{
								if ( $i == count($socskills1)-1 ) { $cbx_class5 = 'wdth700 wdthPr'; } else { $cbx_class5 = 'checktxt'; }
				?>
				<div class="dropdown_row">
					<div class="for_removeBor"> 
						<div class="<?php echo $cbx_class5; ?>">
							<div class="<?php if ( $json_note['social_skill'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="social_skill<?php echo $i;?>" onclick="check1(this, 5, <?php echo (600+$i); ?>); <?php if ( $i == 16 ) { echo 'checkShowInputNew(this,\'dropdw_input6\')'; } ?>"> 
							</div><div class="<?php if ( $i == 16 ) { echo 'last_check'; } else { echo 'after_check1'; } ?>"><?php echo $socskills1[$i]; ?></div> 
							<?php if ( $i == 16 ) { ?><div class="div_for_inp blk"><textarea id="dropdw_input6" type="text" class="area_note_work1 wdthAndMargin" style="<?php if ( $i == 16 && $json_note['social_skill16'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['onlysocskill_other'] !== '' ) { echo $json_note['onlysocskill_other'] ; }?></textarea></div><?php } ?>
						</div><?php if ( $i < 16 ) { ?><div class="dropright">
							<div class="innerdrop" id="skills<?php echo (600+$i); ?>" onclick="dropdown(<?php echo (600+$i); ?>)"  data-valsel = "<?php echo intval($json_note['skills'.(600 + $i)]); ?>">
							<?php  
								$aa6 = 0;
								
								for ( $ba6 = 0; $ba6 < count($drop_skills); $ba6++  )
									{
										if ( $json_note['assist_'.(600+$i).'_'.$ba6] == '1' )
										{
											$aa6 = $aa6 + 1;
										}
									}
								$ca6 += 100; 
								if ( $aa6 > 0 ) { echo 'Selected:<span class="selected_skills" id="span'.(600+$i).'">'.$aa6.' items</span>'; } else { echo 'Independently'; } 				
							?>
							</div> 
							<div class="drop_active" id="drop<?php echo (600+$i); ?>" style="display: none" onmouseout="isin['drop<?php echo (600+$i); ?>']=false" onmouseover="isin['drop<?php echo (600+$i); ?>']=true">
								<?php
									for ( $a = 0; $a< count($drop_skills); $a++ ) 
										{	
											$aee = 0;
											$numCbx = $a+($i*100);
											if ( $a == count($drop_skills)-1 ) { $nuu = '1'; } else { $nuu = '0'; }
											if ( $json_note['assist_'.(600+$i).'_'.$a] && $json_note['assist_'.(600+$i).'_'.$a] == '1' ) { $cbxSql = 'checkbox_checked'; $aee += 1; $span1 = 'Selected:<span class="selected_skills" id="span'.(600+$i).'">'+$aee+'</span>';  } else { $cbxSql = 'checkbox'; }
											if ( $a !== 0 ) { $cbx_and_div = '<div id="assist_'.(600+$i).'_'.$a.'" class="'.$cbxSql.'" onclick="opnOther(this, divFInput'.$i.', '.$nuu.') "></div>'; } else { $cbx_and_div = '<div class="withoutCbx"></div>'; }	
											echo '<div class="cbxAndAssistance" onclick="choseSkillsNew(this, '.(600+$i).', '.$a.','.$numCbx.', 5, \'social_skill'.$i.'\')">'.$cbx_and_div.'<div class="dropdown_div dropInline" >'.$drop_skills[$a].'</div></div>';
											if ( $a == count($drop_skills)-1 ) { ?><div class="pad6" id="divFInput_<?php echo (600+$i).'_'.$a.'_1'; ?>" style="<?php if($json_note['assist_'.(600+$i).'_'.$a] && $json_note['assist_'.(600+$i).'_'.$a] == '1' ) { echo 'display: block'; } else { echo 'display: none'; } ?>"><textarea id="assist_<?php echo (600+$i).'_'.$a.'_1'; ?>" data-other="assist_<?php echo (600+$i).'_'.$a; ?>" type="text" class="area_note_work1 wthproc" ><?php if ( $json_note['assist_'.(600+$i).'_'.$a.'_1'] && $json_note['assist_'.(600+$i).'_'.$a.'_1'] !== '' ) { echo $json_note['assist_'.(600+$i).'_'.$a.'_1']; } ?></textarea></div><?php }
										}
								?>
								<div class="div_btnn"><div class="blue_botton1" style="width: 100px; " onclick="backDrop(this, <?php echo (600+$i); ?>);">Done</div></div>
							</div>
							<div><input type="text" value="<?php if($json_note['iot6'.$i] || $json_note['iot60'.$i]) { if($i > 9 ) { echo $json_note['iot6'.$i]; } else { echo $json_note['iot60'.$i]; } } ?>" style="display: none;" class="input_other_skills" id="iot_<?php echo (600+$i); ?>" /></div>
						</div>
						<?php }		?>
					</div> 
				</div>
				<?php 	 } 		?>
			</div>
			<div class="categories_drop_down" id="section_name7" onclick="toggleSection(7)"><span style="font-family: Helvetica4;">Behavioral Skills</span><div class="absolute_selected">Selected:<span class="selected_skills" id="skills6">
			<?php
				$behs = 0;
				$behskills = array('Decrease aggression', 'Decrease tantrums', 'Improve participation', 'Self soothe', 'Improve attention span/focus', 'Modeling', 'Greetings', 'Peer interaction', 'Other');
				for ( $b = 0; $b < count($behskills); $b++  )
					{
						if ( $json_note['behavioral_skill'.$b] == '1' )
						{
							$behs = $behs + 1;
						}
					}
				if ( $behs > 0 ) { echo $behs.' skills'; } else { echo '0 skills'; }
			?>
			</span></div></div>
			<div class="open_dropdown" id="section7"  style="display: none" >
				<?php 
					
					$ca7 = 0;
					for ( $i=0; $i < count($behskills); $i++ )
						{
							if ( $i == count($behskills)-1 ) { $cbx_class6 = 'wdth700 wdthPr'; } else { $cbx_class6 = 'checktxt'; }
				?>
				<div class="dropdown_row">
					<div class="for_removeBor"> 
						<div class="<?php echo $cbx_class6; ?>">
							<div class="<?php if ( $json_note['behavioral_skill'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="behavioral_skill<?php echo $i;?>" onclick="check1(this, 6, <?php echo (700+$i); ?>); <?php if ( $i == 8 ) { echo 'checkShowInputNew(this,\'dropdw_input7\')'; } ?>"> 
							</div><div class="<?php if ( $i == 8 ) { echo 'last_check'; } else { echo 'after_check1'; } ?>"><?php echo $behskills[$i]; ?></div> 
							<?php if ( $i == 8 ) { ?><div class="div_for_inp blk"><textarea id="dropdw_input7" type="text" class="area_note_work1 wdthAndMargin" style="<?php if ( $i == 8 && $json_note['behavioral_skill8'] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>"><?php if ( $json_note['bskill_other'] !== '' ) { echo $json_note['bskill_other'] ; }?></textarea></div><?php } ?>
						</div><?php if ( $i < 8 ) { ?><div class="dropright">
							<div class="innerdrop" id="skills<?php echo (700+$i); ?>" onclick="dropdown(<?php echo (700+$i); ?>)"  data-valsel="<?php echo intval($json_note['skills'.(700 + $i)]); ?>">
							<?php  
								$aa7 = 0;
								
								for ( $ba7 = 0; $ba7 < count($drop_skills); $ba7++  )
									{
										if ( $json_note['assist_'.(700+$i).'_'.$ba7] == '1' )
										{
											$aa7 = $aa7 + 1;
										}
									}
								$ca7 += 100; 
								if ( $aa7 > 0 ) { echo 'Selected:<span class="selected_skills" id="span'.(700+$i).'">'.$aa7.' items</span>'; } else { echo 'Independently'; } 				
							?>
							</div> 
							<div class="drop_active" id="drop<?php echo (700+$i); ?>" style="display: none" onmouseout="isin['drop<?php echo (700+$i); ?>']=false" onmouseover="isin['drop<?php echo (700+$i); ?>']=true">
								<?php
									for ( $a = 0; $a< count($drop_skills); $a++ ) 
										{	
											$aee = 0;
											$numCbx = $a+($i*100);
											if ( $a == count($drop_skills)-1 ) { $nuu = '1'; } else { $nuu = '0'; }
											if ( $json_note['assist_'.(700+$i).'_'.$a] && $json_note['assist_'.(700+$i).'_'.$a] == '1' ) { $cbxSql = 'checkbox_checked'; $aee += 1; $span1 = 'Selected:<span class="selected_skills" id="span'.(700+$i).'">'+$aee+'</span>';  } else { $cbxSql = 'checkbox'; }
											if ( $a !== 0 ) { $cbx_and_div = '<div id="assist_'.(700+$i).'_'.$a.'" class="'.$cbxSql.'" onclick="opnOther(this, divFInput'.$i.', '.$nuu.') "></div>'; } else { $cbx_and_div = '<div class="withoutCbx"></div>'; }	
											echo '<div class="cbxAndAssistance" onclick="choseSkillsNew(this, '.(700+$i).', '.$a.','.$numCbx.', 6, \'behavioral_skill'.$i.'\')">'.$cbx_and_div.'<div class="dropdown_div dropInline" >'.$drop_skills[$a].'</div></div>';
											if ( $a == count($drop_skills)-1 ) { ?><div class="pad6" id="divFInput_<?php echo (700+$i).'_'.$a.'_1'; ?>" style="<?php if($json_note['assist_'.(700+$i).'_'.$a] && $json_note['assist_'.(700+$i).'_'.$a] == '1' ) { echo 'display: block'; } else { echo 'display: none'; } ?>"><textarea id="assist_<?php echo (700+$i).'_'.$a.'_1'; ?>" data-other="assist_<?php echo (700+$i).'_'.$a; ?>" type="text" class="area_note_work1 wthproc" ><?php if ( $json_note['assist_'.(700+$i).'_'.$a.'_1'] && $json_note['assist_'.(700+$i).'_'.$a.'_1'] !== '' ) { echo $json_note['assist_'.(700+$i).'_'.$a.'_1']; } ?></textarea></div><?php }
										}
								?>
								<div class="div_btnn"><div class="blue_botton1" style="width: 100px; " onclick="backDrop(this, <?php echo (700+$i); ?>);">Done</div></div>
							</div>
							<div><input type="text" style="display: none;" value="<?php if($json_note['iot7'.$i] || $json_note['iot70'.$i]) { if($i > 9 ) { echo $json_note['iot7'.$i]; } else { echo $json_note['iot70'.$i]; } } ?>" class="input_other_skills" id="iot_<?php echo (700+$i); ?>" /></div>
						</div>
						<?php }	?>
					</div> 
				</div>
				<?php 	 } 		?>
			</div>
		</div>
		<div class="part_div"> 
			<div class="paddingb">
				<div class="name_of_title">Caregiver preferred learning styles:</div>
				<div class="iblock">
				<?php
					$caregivers_styles = array('Adaptation of materials', 'Gesture with verbal cues', 'Hand-over-hand', 'Instructions step by step', 'Interventionist models', 'Modification of environment', 'Opportunities to practice', 'Parent models', 'Positioning', 'Physical prompts', 'Verbal cues', 'Visual cues', 'Use of Assistive Technology', 'Other');
					for ( $i=0; $i < count($caregivers_styles); $i++ )
						{	
							if ( $i !== (count($caregivers_styles))-1) 
								{
				?>
									<div class="paddingt">
										<div class="<?php if ( $json_note['caregiverlearn_style'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="caregiverlearn_style<?php echo $i; ?>" onclick="check(this)">
										</div><div class="after_check"><?php echo $caregivers_styles[$i]; ?></div>
									</div>
							<?php 
								}
							else	
								{
							?>
									<div class="carg_width">
										<div class="checkoth">
											<div class="<?php if ( $json_note['caregiverlearn_style'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="caregiverlearn_style<?php echo $i; ?>" onclick="check(this); checkShowInputNew(this,'other_input4');">
											</div><div class="after_check"><?php echo $caregivers_styles[$i]; ?></div>
										</div>
										<div class="div_for_inp blk"><textarea id="other_input4"type="text" class="area_note_work no_mrg" style="<?php if ( $json_note['caregiverlearn_style'.$i] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>; vertical-align: middle;"><?php if ( $json_note['caregiver_learns'] !== '' ) { echo $json_note['caregiver_learns'] ; }?></textarea></div>
									</div>		
				<?php
								} 
						} 		
				?>
				</div>
			</div>
			<div class="paddingb">
				<div class="name_of_title">Strategies used by interventionist:</div>
				<div class="iblock">
				<?php
					$strategies = array('Caregiver practice w/ feedback', 'Demonstration', 'Direct service', 'Guided practice w/feedback ', 'Problem solving', 'Reflection', 'Other');
					for ( $i=0; $i < count($strategies); $i++ )
						{	
							if ( $i !== (count($strategies))-1) 
								{
				?>
									<div class="paddingt">
										<div class="<?php if ( $json_note['strategies_by_inter'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="strategies_by_inter<?php echo $i; ?>" onclick="check(this)">
										</div><div class="after_check"><?php echo $strategies[$i]; ?></div>
									</div>
							<?php 
								}
							else	
								{
							?>
									<div class="carg_width"> 
										<div class="checkoth">
											<div class="<?php if ( $json_note['strategies_by_inter'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="strategies_by_inter<?php echo $i; ?>" onclick="check(this); checkShowInputNew(this,'other_input5');">
											</div><div class="after_check"><?php echo $strategies[$i]; ?></div>
										</div>
										<div class="div_for_inp blk"><textarea id="other_input5"type="text" class="area_note_work no_mrg" style="<?php if ( $json_note['strategies_by_inter'.$i] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>; vertical-align: middle;"><?php if ( $json_note['strategies_by_inter'] !== '' ) { echo $json_note['strategies_by_inter'] ; }?></textarea></div>
									</div>		

				<?php 
								} 
						} 	
				?>
				</div>
			</div>
			<div class="paddingb">
				<div class="name_of_title">Caregiver and Interventionist’s plan. Activities caregiver will practice between visits: </div>
				<div class="iblock">
					<div><textarea id="area_new2" class="area_note_work1"><?php if ( $json_note['previus_cargiveplan_area1'] !== '' ) { echo $json_note['previus_cargiveplan_area1'];  } ?></textarea></div>
				</div>
			</div>
			<div class="paddingb">
				<div class="name_of_title" >Joint plan to practice next session:</div>
				<div class="iblock">
					<div class="check_deevs"><div class="<?php if ( $json_note['join_plan1'] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="join_plan1" onclick="check(this); disableAtribute();"></div><div class="after_check">Same as above</div></div>
					<div><textarea id="area_new3"  <?php if ( $json_note['join_plan1'] == '1' ) { echo 'disabled="disabled"'; } ?> class="area_note_work"><?php if ( $json_note['prev_joint_plan_area2'] !== '' ) { echo $json_note['prev_joint_plan_area2'];  } ?></textarea></div>
				</div>
			</div>
			<div class="paddingb">
				<div class="name_of_title"> Interventionist feedback on caregiver’s participation:</div>
				<div class="group_checkb">
				<?php
					$participaticion = array('Reported progress/concerns', 'Practiced strategies', 'Asked questions', 'Productive', 'Solved problems', 'Shared information', 'Observed', 'Engaged', 'Not engaged (preferred to observe)', 'Other');
					for ( $i=0; $i < count($participaticion); $i++ )
						{	
							if ( $i == 3 || $i == 7 ){ $new_br = 'check_deevs_with_padding_bottom'; } else if ( $i ==  ((count($participaticion)) - 1) ) { $new_br = ' padding_top5px'; } else if ( $i == 8 ) { $new_br = 'check_devs_pad_top'; } else { $new_br = 'check_deevs';  }
							if ( $i !== (count($participaticion))-1 ) 
								{
				?>
									<div class="<?php echo $new_br; ?>">
										<div class="<?php if ( $json_note['interv_feedback'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="interv_feedback<?php echo $i; ?>" onclick="check(this)">
										</div><div class="after_check"><?php echo $participaticion[$i]; ?></div>
									</div>
							<?php 
								}
							else	
								{
							?>
									<div class="paddtop">
										<div class="checkoth">
											<div class="<?php if ( $json_note['interv_feedback'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="interv_feedback<?php echo $i; ?>" onclick="check(this); checkShowInputNew(this,'other_input7');">
											</div><div class="after_check"><?php echo $participaticion[$i]; ?></div>
										</div>
										<div class="div_for_inp blk"><textarea id="other_input7" type="text" class="area_note_work no_mrg" style="<?php if ( $json_note['interv_feedback'.$i] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>; vertical-align: middle;"><?php if ( $json_note['inter_feedback'] !== '' ) { echo $json_note['inter_feedback'] ; }?></textarea></div>
									</div>		

				<?php 	
								} 
						}		
				?>
				</div>
			</div>
			<div class="paddingb">
				<div class="name_of_title">Child’s progress/performance:</div>
				<div class="group_checkb">
				<?php
					$performance = array('Progressing', 'Plateau', 'Low attention span', 'Engaged', 'Not engaged', 'Low motivation', 'High motivation', 'Other');
					for ( $i=0; $i < count($performance); $i++ )
						{	
							if ( $i == 4 ) { $new_br = 'check_deevs_with_padding_bottom'; } else if ( $i ==  ((count($performance)) - 1)) { $new_br = ' padding_top4px'; } else if ( $i == 5 ) { $new_br = 'check_devs_pad_top check_deevs';  } else { $new_br = 'check_deevs';  }
							if ( $i !== (count($performance))-1 ) 
								{
				?>
									<div class="<?php echo $new_br; ?>">
										<div class="<?php if ( $json_note['childs_progres'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="childs_progres<?php echo $i; ?>" onclick="check(this)">
										</div><div class="after_check"><?php echo $performance[$i]; ?></div>
									</div>
							<?php 
								}
							else	
								{
							?>
									<div class="paddingt">
										<div class="checkoth">
											<div class="<?php if ( $json_note['childs_progres'.$i] == '1' ) { echo 'checkbox_checked'; } else { echo 'checkbox'; } ?>" id="childs_progres<?php echo $i; ?>" onclick="check(this); checkShowInputNew(this,'other_input8');">
											</div><div class="after_check"><?php echo $performance[$i]; ?></div>
										</div>
										<div class="div_for_inp blk"><textarea id="other_input8" type="text" class="area_note_work no_mrg" style="<?php if ( $json_note['childs_progres'.$i] == '1' ) { echo 'display: inline-block'; } else {  echo 'display: none'; }  ?>; vertical-align: middle;"><?php if ( $json_note['childs_progress'] !== '' ) { echo $json_note['childs_progress'] ; }?></textarea></div>
									</div>		
				<?php 
								} 
						}
						
				?>
				</div>
			</div>
			<div class="paddingb">
				<div class="name_of_title">Additional notes:</div>
				<div class="div_for_inp"><textarea class="area_note_work no_mrg" id="inp_title" ><?php if ( $json_note['title'] && $json_note['title'] !== '' ) { echo $json_note['title'];  }   ?></textarea></div>
			</div>
			<div class="name_of_title" style="vertical-align: middle;">Additional resources to assist with practice:</div>
			<form target="target_frame" method="POST" enctype="multipart/form-data" class="addcom_form1"> 
				<div class="addcom_squer">
					<input type="file" id="input_chang" name="squere" class="squere_file" onchange="this.form.submit(); loading();" />  
					<div id="fdiv_1" class="squere no_mb" style="<?php if ( $json_note['fileup'][0] ) { echo 'background-image: url(\'/uploads/'.$json_note['fileup'][0].'\'); background-size: cover;'; } ?>"><?php if ( $json_note['fileup'][0] ) { ?><input type="hidden" id="file_up_1" name="file_up[]" value="<?php echo $json_note['fileup'][0]; ?>" /><?php } ?></div>
					<div id="fdiv_2" class="squere no_mb" style="<?php if ( $json_note['fileup'][1] ) { echo 'background-image: url(\'/uploads/'.$json_note['fileup'][1].'\'); background-size: cover;'; } ?>"><?php if ( $json_note['fileup'][1] ) { ?><input type="hidden" id="file_up_2" name="file_up[]" value="<?php echo $json_note['fileup'][1]; ?>" /><?php } ?></div>
					<div id="fdiv_3" class="squere no_mb" style="<?php if ( $json_note['fileup'][2] ) { echo 'background-image: url(\'/uploads/'.$json_note['fileup'][2].'\'); background-size: cover;'; } ?>"><?php if ( $json_note['fileup'][2] ) { ?><input type="hidden" id="file_up_3" name="file_up[]" value="<?php echo $json_note['fileup'][2]; ?>" /><?php } ?></div>
					<div id="fdiv_4" class="squere no_mb" style="<?php if ( $json_note['fileup'][3] ) { echo 'background-image: url(\'/uploads/'.$json_note['fileup'][3].'\'); background-size: cover;'; } ?>"><?php if ( $json_note['fileup'][3] ) { ?><input type="hidden" id="file_up_4" name="file_up[]" value="<?php echo $json_note['fileup'][3]; ?>" /><?php } ?></div>
					<div id="fdiv_5" class="squere no_mb" style="<?php if ( $json_note['fileup'][4] ) { echo 'background-image: url(\'/uploads/'.$json_note['fileup'][4].'\'); background-size: cover;'; } ?>"><?php if ( $json_note['fileup'][4] ) { ?><input type="hidden" id="file_up_5" name="file_up[]" value="<?php echo $json_note['fileup'][4]; ?>" /><?php } ?></div>
					<div id="fdiv_6" class="squere no_mb" style="<?php if ( $json_note['fileup'][5] ) { echo 'background-image: url(\'/uploads/'.$json_note['fileup'][5].'\'); background-size: cover;'; } ?>"><?php if ( $json_note['fileup'][5] ) { ?><input type="hidden" id="file_up_6" name="file_up[]" value="<?php echo $json_note['fileup'][5]; ?>" /><?php } ?></div>
				</div>
				<div id="loader"></div>
			</form>
			<div class="ar_button">
				 <div class="blue_botton1" onclick="addNote(<?php echo $id; ?>, 1, <?php echo $new; ?>)">Submit</div></a>
				 <div class="but_middle"> </div>
				 <div class="blue_botton1" onclick="addNote(<?php echo $id; ?>, 0, <?php echo $new; ?>)">Save</div>
			</div>
		</div>
		<iframe name="target_frame" style="display: none;"> </iframe>
	</div>
<?php
	require_once('footer.php');
?>