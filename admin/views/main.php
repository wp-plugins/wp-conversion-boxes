<?php   

/*************************************
* Main admin page. Lists all boxes.
*************************************/

$wpcb_license = WPCB_Licensing::get_instance();

if(!$wpcb_license->is_license_valid()){
    $wpcb_license->license_activation_form();
}
else{
    $wpcb_license->license_renew_notice();
    $wpcb =  WPCB_Admin::get_instance();
    $wpcb_public = WPCB_Public::get_instance();
    $wpcb_tracking = WPCB_Tracker::get_instance();

    if(isset($_GET['duplicate'])){
        $dupli_box_id = $_GET['duplicate'];
        $wpcb_duplicate_created = $wpcb->wpcb_duplicate_box($dupli_box_id);
        if($wpcb_duplicate_created == true){
            echo "<div class='updated'><p>". __('Successfully Duplicated!', 'wp-conversion-boxes-pro') ."</p></div>";
        }
        else{
            echo "<div class='error'><p>". __('Box has not been duplicated. There was some error.', 'wp-conversion-boxes-pro') ."</p></div>";
        }
    }

    if(isset($_GET['create-ab-test'])){
        $create_ab_test_id = $_GET['create-ab-test'];
        if($wpcb->wpcb_create_ab_test($create_ab_test_id)){
            echo "<div class='updated'><p>". __('Test Successfully Created!', 'wp-conversion-boxes-pro') ."</p></div>";
        }
        else{
            echo "<div class='error'><p>". __('Test has not been created. There was some error.', 'wp-conversion-boxes-pro') ."</p></div>";
        }
    }

?>

<div class="wrap wpcb-wrapper">
    <h2>
        <?php
        if(!isset($_GET['allstats']) && !isset($_GET['id'])){
             echo esc_html( get_admin_page_title() ). ' ' . __('Dashboard' , 'wp-conversion-boxes-pro') .' <a href="'.admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ).'" class="add-new-h2">'. __('Add New', 'wp-conversion-boxes-pro') .'</a>'; 
        }
        else{
            echo __('Detailed Conversion Stats for' , 'wp-conversion-boxes-pro'). " <em><b>".$wpcb->get_box_name($_GET['id'])."</b></em>";
        }
        ?>
    </h2>
    
    <div id="poststuff" class="metabox-holder has-right-sidebar">
    
        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>

            <div id="post-body-content">
            <?php     
                if(!isset($_GET['allstats']) && !isset($_GET['id'])){
                    $wpcb->wpcb_show_boxes_list();
                }
                else{
                    $box_id = (isset($_GET['id'])) ? $_GET['id'] : '';
                    $action = (isset($_GET['allstats'])) ? $_GET['allstats'] : '';
                    $startdate = date('m/d/Y', strtotime('-30 days'));
                    $enddate = date("m/d/Y");
                    $graphdata = $wpcb_tracking->get_stats_for_graph($box_id, $startdate, $enddate);
            ?>
                    <div class='postbox'>
                        <h3><?php _e('Daily Traffic and Conversion Graph', 'wp-conversion-boxes-pro'); ?></h3>
                        <div class='inside' style="height: 520px;">
                            <p><?php _e('Select a date range below to show traffic and conversion statistics for that particular date range.', 'wp-conversion-boxes-pro'); ?></p>
                            <div class='wpcb_stats_filters'>
                                <form method="POST" action="" style="display: inline-block;">
                                    <label><i class="fa fa-calendar"></i> <?php _e('Date Range:', 'wp-conversion-boxes-pro'); ?> <input type="text" style="width: 200px" name="wpcb_date_range" id="wpcb_date_range" class="form-control" value="<?php echo $startdate; ?> - <?php echo $enddate; ?>" /></label>
                                </form>
                            </div>
                            <div class="wpcb_stats_loading"><img src="<?php echo plugins_url( 'assets/imgs/loading1.gif', dirname(__FILE__) ); ?>"></div>
                            <div class="wpcb_stats_graph_wrap">
                                <div id="placeholder" class="demo-placeholder" style="width: 90%; height: 270px; margin: 30px; padding: 0px; position: relative;"></div>
                                <p id="choices" style="line-height:30px; padding-bottom:6px; text-align: center;"></p>
                                <div id="overview" style="margin: 0 auto; width: 60%; height: 50px; padding: 0px; position: relative;"></div>
                                <p class="wpcb_help_block" style="text-align: center;"><?php _e('Overview (Zoom in/out of the graph)', 'wp-conversion-boxes-pro'); ?></p>
                                <script>
                                    var d = {
                                        data: [<?php echo $graphdata['uniquevisitors']; ?>], 
                                        label: "<?php _e('Unique Visitors', 'wp-conversion-boxes-pro'); ?>", 
                                        color: "#FFC200"
                                    };
                                    var d2 = {
                                        data: [<?php echo $graphdata['pageviews']; ?>],
                                        label: "<?php _e('Pageviews', 'wp-conversion-boxes-pro'); ?>",
                                        color: "#FF6820"
                                    };
                                    var d3 = {
                                        data: [<?php echo $graphdata['boxviews']; ?>],
                                        label: "<?php _e('Box Views', 'wp-conversion-boxes-pro'); ?>",
                                        color: "#1180A7"
                                    };
                                    var d4 = {
                                        data: [<?php echo $graphdata['conversions']; ?>],
                                        label: "<?php _e('Conversions', 'wp-conversion-boxes-pro'); ?>",
                                        color: "#0bc"
                                    };
                                    var datasets = [d, d2, d3, d4];
                                    var options = {
                                        series: {
                                            lines: {
                                                show: true,
                                                lineWidth: 4
                                            },
                                            points: {
                                                show: true
                                            }
                                        },
                                        legend: {
                                            show: false
                                        },
                                        grid: {
                                            hoverable: true,
                                            clickable: true
                                        },
                                        yaxes: [{
                                            min: 0,
                                            minTickSize: 1
                                        }],
                                        selection: {
                                            mode: "x"
                                        },
                                        xaxis: {
                                            mode: "time",
                                            minTickSize: [1, "day"],
                                            timeformat: "%m/%d/%y"
                                        }
                                    };

                                    function showTooltip(x, y, contents) {
                                        jQuery('<div id="tooltip">' + contents + '</div>').css({
                                            position: 'absolute',
                                            display: 'none',
                                            top: y - 15,
                                            left: x + 10,
                                            border: '1px solid #add',
                                            padding: '4px',
                                            'background-color': '#eff',
                                            opacity: 0.90
                                        }).appendTo("body").fadeIn(50);
                                    }
                                    var previousPoint = null;
                                    jQuery("#placeholder").bind("plothover", function (event, pos, item) {
                                        if (item) {
                                            var x = item.datapoint[0].toFixed(2),
                                                y = item.datapoint[1].toFixed(2);
                                            if (previousPoint != item.dataIndex) {
                                                previousPoint = item.dataIndex;
                                                jQuery("#tooltip").remove();
                                                var date = new Date(item.datapoint[0] - -3600000);
                                                var month = date.getMonth();
                                                var datea = date.getDate();
                                                var day = date.getDay();
                                                var hour = date.getHours();
                                                var monthNames = [ "Jan", "Feb", "Mar", "Apr", "May", "June","July", "Aug", "Sept", "Oct", "Nov", "Dec" ];
                                                var dayNames = ["Sunday","Monday","Tuesday","Wednesday","Thursday","Friday","Saturday"]
                                                var x = monthNames[month] + " " + datea + ", " + date.getFullYear() + " (" + dayNames[day] + ")";
                                                y = item.datapoint[1].toFixed(0);
                                                showTooltip(item.pageX, item.pageY, y + " " + item.series.label + " on " + x);
                                            }
                                        } else {
                                            jQuery("#tooltip").remove();
                                            previousPoint = null;
                                        }
                                    });
                                    var choiceContainer = jQuery("#choices");

                                    jQuery.each(datasets, function (key, val) {
                                        choiceContainer.append('<a style="line-height:10px; font-size:14px; margin-left:0px; margin-right:8px; display:inline-block;"><input type="checkbox" name="' + key + '" checked="checked" id="id' + key + '" style="display:inline-block; width:16px;">' + '<label for="id' + key + '" style="margin-left:4px; margin-right:10px; font-weight:bold; color:' + val.color + '">' + val.label + '</label></a>');
                                    });

                                    choiceContainer.find("input").click(function(){
                                            plotAccordingToChoices(false,false);
                                    });

                                    function plotAccordingToChoices(wpcb_datasets, wpcb_options) {
                                        if(wpcb_datasets){
                                            datasets = wpcb_datasets;
                                        }
                                        if(wpcb_options){
                                            options = wpcb_options;
                                        }
                                        var data = [];
                                        choiceContainer.find("input:checked").each(function () {
                                            var key = jQuery(this).attr("name");
                                            if (key && datasets[key]) {
                                                data.push(datasets[key]);
                                            }
                                        });
                                        if (data.length > 0) {
                                            jQuery("#placeholder").bind("plotselected", function (event, ranges) {
                                                plot = jQuery.plot(jQuery("#placeholder"), data, jQuery.extend(true, {}, options, {
                                                    xaxis: {
                                                        min: ranges.xaxis.from,
                                                        max: ranges.xaxis.to
                                                    }
                                                }));
                                                overview.setSelection(ranges, true);
                                            });
                                            jQuery("#overview").bind("plotselected", function (event, ranges) {
                                                plot.setSelection(ranges);
                                            });
                                            var plot = jQuery.plot(jQuery("#placeholder"), data, options);
                                            var overview = jQuery.plot(jQuery("#overview"), data, {
                                                series: {
                                                    lines: {
                                                        show: true,
                                                        lineWidth: 2
                                                    },
                                                    shadowSize: 0
                                                },
                                                xaxis: {
                                                    ticks: [],
                                                    mode: "time"
                                                },
                                                yaxis: {
                                                    ticks: [],
                                                    min: 0,
                                                    autoscaleMargin: 0.1
                                                },
                                                selection: {
                                                    mode: "x"
                                                },
                                                legend: {
                                                    show: false
                                                }
                                            });
                                        }
                                    }
                                    plotAccordingToChoices();
                                    
                                    // Run this on date range change

                                    jQuery(document).on('apply.daterangepicker','#wpcb_date_range', function(){

                                        var $wpcbStatsGraphWrap = jQuery('.wpcb_stats_graph_wrap');
                                        var $wpcbStatsLoading = jQuery('.wpcb_stats_loading');
                                        var $wpcbStatsListWrap = jQuery('.wpcb_stats_list_wrap');
                                        var $wpcbStatsListLoading = jQuery('.wpcb_stats_list_loading');
                                        
                                        $wpcbStatsGraphWrap.css('visibility', 'hidden');
                                        $wpcbStatsLoading.show();
                                        $wpcbStatsListWrap.hide();
                                        $wpcbStatsListLoading.show();
                                        
                                        var wpcbDateRange = jQuery(this).val();
                                        var wpcbDates = wpcbDateRange.split(' - ')
                                        var wpcbStartDate = wpcbDates[0];
                                        var wpcbEndDate = wpcbDates[1];

                                        var data = {
                                            action: 'fetch_graph_data',
                                            box_id: <?php echo $box_id; ?>,
                                            startdate: wpcbStartDate,
                                            enddate: wpcbEndDate
                                        };

                                        jQuery.post(ajaxurl, data, function(response) {
                                            if(response){
                                                
                                                var wpcbAllStats = jQuery.parseJSON(response);
                                                
                                                var wpcbuniquevisitors = wpcbAllStats.uniquevisitors.substring(0, wpcbAllStats.uniquevisitors.length - 1);
                                                var wpcbpageviews = wpcbAllStats.pageviews.substring(0, wpcbAllStats.pageviews.length - 1);
                                                var wpcboxviews = wpcbAllStats.boxviews.substring(0, wpcbAllStats.boxviews.length - 1);
                                                var wpcbconversions = wpcbAllStats.conversions.substring(0, wpcbAllStats.conversions.length - 1);
                                                
                                                var wpcbListCode = wpcbAllStats.listdata;
                                                    
                                                wpcbuniquevisitors = JSON.parse("[" + wpcbuniquevisitors + "]");
                                                wpcbpageviews = JSON.parse("[" + wpcbpageviews + "]");
                                                wpcboxviews = JSON.parse("[" + wpcboxviews + "]");
                                                wpcbconversions = JSON.parse("[" + wpcbconversions + "]");

                                                var d = {
                                                    data: wpcbuniquevisitors, 
                                                    label: "<?php _e('Unique Visitors', 'wp-conversion-boxes-pro'); ?>", 
                                                    color: "#FFC200"
                                                };
                                                var d2 = {
                                                    data: wpcbpageviews, 
                                                    label: "<?php _e('Pageviews', 'wp-conversion-boxes-pro'); ?>",
                                                    color: "#FF6820"
                                                };
                                                var d3 = {
                                                    data: wpcboxviews,
                                                    label: "<?php _e('Box Views', 'wp-conversion-boxes-pro'); ?>",
                                                    color: "#1180A7"
                                                };
                                                var d4 = {
                                                    data: wpcbconversions,
                                                    label: "<?php _e('Conversions', 'wp-conversion-boxes-pro'); ?>",
                                                    color: "#0bc"
                                                };
                                                var datasets = [d, d2, d3, d4];
                                                var options = {
                                                    series: {
                                                        lines: {
                                                            show: true,
                                                            lineWidth: 4
                                                        },
                                                        points: {
                                                            show: true
                                                        }
                                                    },
                                                    legend: {
                                                        show: false
                                                    },
                                                    grid: {
                                                        hoverable: true,
                                                        clickable: true
                                                    },
                                                    yaxes: [{
                                                        min: 0,
                                                        minTickSize: 1
                                                    }],
                                                    selection: {
                                                        mode: "x"
                                                    },
                                                    xaxis: {
                                                        mode: "time",
                                                        minTickSize: [1, "day"],
                                                        timeformat: "%m/%d/%y"
                                                    }
                                                };

                                                plotAccordingToChoices(datasets,options);
                                                
                                                $wpcbStatsGraphWrap.css('visibility', 'visible');
                                                $wpcbStatsLoading.hide();
                                                $wpcbStatsListWrap.html(wpcbListCode);
                                                $wpcbStatsListWrap.show();
                                                $wpcbStatsListLoading.hide();
                                                jQuery('#wpcb_stats_list_table').dataTable();
                                            }
                                            else
                                            {
                                                $wpcbStatsGraphWrap.css('visibility', 'visible');
                                                $wpcbStatsLoading.hide();
                                                $wpcbStatsListWrap.show();
                                                $wpcbStatsListLoading.hide();
                                                alert('<?php _e('There was an error fetching info. Please try again later or contact support@wpconversionboxes.com if problem persists.', 'wp-conversion-boxes-pro'); ?>');
                                            }

                                        });
                                    });


                                </script>
                            </div>
                        </div>
                    </div>
                    <div class='postbox'>
                        <h3><?php _e('Top Performing Posts/Pages', 'wp-conversion-boxes-pro'); ?></h3>
                        <div class='inside'>
                            <p><?php _e('Top performing posts and pages for above selected date range. Change the date range above to change the data shown below.', 'wp-conversion-boxes-pro'); ?></p>
                            <div class="wpcb_stats_list_loading"><img src="<?php echo plugins_url( 'assets/imgs/loading1.gif', dirname(__FILE__) ); ?>" /></div>
                            <div class="wpcb_stats_list_wrap">
                                <?php $wpcb_tracking->visit_details($box_id, $startdate, $enddate); ?>
                            </div>
                        </div>
                    </div>
            <?php
                
                }
            ?>    
            </div>
    </div> 

</div>

<?php } // End if ?>