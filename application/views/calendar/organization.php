<?php
CI_Controller::get_instance()->load->helper('language');
$this->lang->load('calendar', $language);
$this->lang->load('status', $language);
$this->lang->load('global', $language);?>

<h1><?php echo lang('calendar_organization_title');?> &nbsp;
<a href="http://www.leave-management-system.org/page-calendar-organization.html" title="<?php echo lang('global_link_tooltip_documentation');?>" target="_blank"><i class="icon-question-sign"></i></a></h1>

<div class="row-fluid">
    <div class="span4">
        <label for="txtEntity"><?php echo lang('calendar_organization_field_select_entity');?></label>
        <div class="input-append">
        <input type="text" id="txtEntity" name="txtEntity" readonly />
        <button id="cmdSelectEntity" class="btn btn-primary"><?php echo lang('calendar_organization_button_select_entity');?></button>
        </div>
    </div>
    <div class="span3">
        <label class="checkbox">
            <input type="checkbox" value="" id="chkIncludeChildren"> <?php echo lang('calendar_organization_check_include_subdept');?>
        </label>
    </div>
    <div class="span5">&nbsp;</div>
</div>
<div class="row-fluid">
    <div class="span3"><span class="label"><?php echo lang('Planned');?></span></div>
    <div class="span3"><span class="label label-success"><?php echo lang('Accepted');?></span></div>
    <div class="span3"><span class="label label-warning"><?php echo lang('Requested');?></span></div>
    <div class="span3">&nbsp;</div>
</div>

<div id="frmSelectEntity" class="modal hide fade">
    <div class="modal-header">
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="close">&times;</a>
         <h3><?php echo lang('calendar_organization_popup_entity_title');?></h3>
    </div>
    <div class="modal-body" id="frmSelectEntityBody">
        <img src="<?php echo base_url();?>assets/images/loading.gif">
    </div>
    <div class="modal-footer">
        <a href="#" onclick="select_entity();" class="btn secondary"><?php echo lang('calendar_organization_popup_entity_button_ok');?></a>
        <a href="#" onclick="$('#frmSelectEntity').modal('hide');" class="btn secondary"><?php echo lang('calendar_organization_popup_entity_button_cancel');?></a>
    </div>
</div>

<div id='calendar'></div>

<link href="<?php echo base_url();?>assets/fullcalendar/fullcalendar.css" rel="stylesheet">
<script type="text/javascript" src="<?php echo base_url();?>assets/fullcalendar/fullcalendar.min.js"></script>
<script type="text/javascript">
    var entity = -1; //Id of the selected entity
    var text; //Label of the selected entity
    
    function select_entity() {
        entity = $('#organization').jstree('get_selected')[0];
        text = $('#organization').jstree().get_text(entity);
        $('#txtEntity').val(text);
        $('#calendar').fullCalendar('removeEvents');
        if ($('#chkIncludeChildren').prop('checked') == true) {
            $('#calendar').fullCalendar('addEventSource', '<?php echo base_url();?>leaves/organization/' + entity + '?children=true');
        } else {
            $('#calendar').fullCalendar('addEventSource', '<?php echo base_url();?>leaves/organization/' + entity + '?children=false');
        }
        $('#calendar').fullCalendar('rerenderEvents');
        $("#frmSelectEntity").modal('hide');
    }

    $(document).ready(function() {

        //Popup select entity
        $("#cmdSelectEntity").click(function() {
            $("#frmSelectEntity").modal('show');
            $("#frmSelectEntityBody").load('<?php echo base_url(); ?>organization/select');
        });

        //On click the check box "include sub-department", refresh the content if a department was selected
        $('#chkIncludeChildren').click(function() {
            $('#calendar').fullCalendar('removeEvents');
            if (entity != -1) {
                if ($('#chkIncludeChildren').prop('checked') == true) {
                    $('#calendar').fullCalendar('addEventSource', '<?php echo base_url();?>leaves/organization/' + entity + '?children=true');
                } else {
                    $('#calendar').fullCalendar('addEventSource', '<?php echo base_url();?>leaves/organization/' + entity + '?children=false');
                }
            }
        });

        //Load alert forms
        $("#frmSelectEntity").alert();
        //Prevent to load always the same content (refreshed each time)
        $('#frmSelectEntity').on('hidden', function() {
            $(this).removeData('modal');
        });

        //Create a calendar and fill it with AJAX events
        $('#calendar').fullCalendar({
            monthNames: [<?php echo lang('calendar_component_monthNames');?>],
            monthNamesShort: [<?php echo lang('calendar_component_monthNamesShort');?>],
            dayNames: [<?php echo lang('calendar_component_dayNames');?>],
            dayNamesShort: [<?php echo lang('calendar_component_dayNamesShort');?>],
            titleFormat: {
                month: '<?php echo lang('calendar_component_titleFormat_month');?>',
                week: "<?php echo lang('calendar_component_titleFormat_week');?>",
                day: '<?php echo lang('calendar_component_titleFormat_day');?>'
            },
            columnFormat: {
                month: '<?php echo lang('calendar_component_columnFormat_month');?>',
                week: '<?php echo lang('calendar_component_columnFormat_week');?>',
                day: '<?php echo lang('calendar_component_columnFormat_day');?>'
            },
            axisFormat: "<?php echo lang('calendar_component_axisFormat');?>",
            timeFormat: {
                '': "<?php echo lang('calendar_component_timeFormat');?>",
                agenda: "<?php echo lang('calendar_component_timeFormat_agenda');?>"
            },
            firstDay: <?php echo lang('calendar_component_firstDay');?>,
            buttonText: {
                today: "<?php echo lang('calendar_component_buttonText_today');?>",
                day: "<?php echo lang('calendar_component_buttonText_day');?>",
                week: "<?php echo lang('calendar_component_buttonText_week');?>",
                month: "<?php echo lang('calendar_component_buttonText_month');?>"
            },
            header: {
                left: "<?php echo lang('calendar_component_header_left');?>",
                center: "<?php echo lang('calendar_component_header_center');?>",
                right: "<?php echo lang('calendar_component_header_right');?>"
            }
        });
    });
</script>
