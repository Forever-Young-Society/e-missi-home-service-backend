
<?php
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $dataProvider yii\data\ActiveDataProvider */
$title = Yii::t('app', 'Availability Slot Calendar');
$this->params['breadcrumbs'][] = Yii::t('app', 'Availability Slot Calendar');
?>
<script>
  document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar');
    var calendar;
    initThemeChooser({
      init: function(themeSystem) {
        calendar = new FullCalendar.Calendar(calendarEl, {
          themeSystem: themeSystem,
          timeZone: 'Asia/Kolkata',
          height: 800,
          contentHeight: 600,
          aspectRatio: '2',
           initialView: 'dayGridMonth',
          initialDate: '<?php

        echo date('Y-m-d')?>',
          weekNumbers: false,
          navLinks: true, // can click day/week names to navigate views
          displayEventTime:false,
          axisFormat: 'H:mm',
 		  timeFormat: {
          agenda: 'H:mm{ - H:mm}a'
 		  },
          nowIndicator: true,
          dayMaxEvents: true, // allow "more" link when too many events
          events: [
          <?php
        if (! empty($events)) {
            foreach ($events as $event) {
                ?>
           {
              title: '<?=$event->title?>',
              start: '<?=$event->start?>',
              end: '<?=$event->end?>',
              backgroundColor: '<?=$event->backgroundColor?>',
              borderColor: '<?=$event->borderColor?>',
              textColor: 'white',
              id: '<?=$event->id?>',
              allDay:true
            },
           <?php
            }
        }
        ?>
          ],
          
            eventTimeFormat: { // like '14:30:00'
                hour: '2-digit',
                minute: '2-digit',
                meridiem: true,
                hour24: true
              },
		
        });
        calendar.render();
      },
      change: function(themeSystem) {
        calendar.setOption('themeSystem', themeSystem);
      }
    });
  });
</script>
<div class="wrapper">
	<div class="card">
		<div class="user-training-index">
				<?=\app\components\PageHeader::widget(['title' => $title]);?>
			</div>
	</div>
	<div class="card">
		<header class="card-header d-flex justify-content-between">
			  <?php

    echo strtoupper(Yii::$app->controller->action->id);
    ?>
		</header>
		<div class="card-body">
			<div class="content-section clearfix">
				<div id='top' class="d-none">
					<div class='left'>
						<div id='theme-system-selector' class='selector'>
							Theme System: <select>
								<option value='bootstrap' selected>Bootstrap 4</option>
								<option value='standard'>unthemed</option>
							</select>
						</div>

						<div data-theme-system="bootstrap" class='selector'
							style='display: none'>
							Theme Name: <select>
								<option value='' selected>Default</option>
								<option value='cerulean'>Cerulean</option>
								<option value='cosmo'>Cosmo</option>
								<option value='cyborg'>Cyborg</option>
								<option value='darkly'>Darkly</option>
								<option value='flatly'>Flatly</option>
								<option value='journal'>Journal</option>
								<option value='litera'>Litera</option>
								<option value='lumen'>Lumen</option>
								<option value='lux'>Lux</option>
								<option value='materia'>Materia</option>
								<option value='minty'>Minty</option>
								<option value='pulse'>Pulse</option>
								<option value='sandstone'>Sandstone</option>
								<option value='simplex'>Simplex</option>
								<option value='sketchy'>Sketchy</option>
								<option value='slate'>Slate</option>
								<option value='solar'>Solar</option>
								<option value='spacelab'>Spacelab</option>
								<option value='superhero'>Superhero</option>
								<option value='united'>United</option>
								<option value='yeti'>Yeti</option>
							</select>
						</div>
						<span id='loading' style='display: none'>loading theme...</span>
					</div>
					<div class='right'>
						<span class='credits' data-credit-id='bootstrap-standard'
							style='display: none'> <a
							href='https://getbootstrap.com/docs/3.3/' target='_blank'>Theme
								by Bootstrap</a>
						</span> <span class='credits' data-credit-id='bootstrap-custom'
							style='display: none'> <a href='https://bootswatch.com/'
							target='_blank'>Theme by Bootswatch</a>
						</span>
					</div>

					<div class='clear'></div>
				</div>

				<div id='calendar'></div>
			</div>
		</div>
	</div>
</div>

<div id="cal"></div>
