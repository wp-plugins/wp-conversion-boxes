<?php   

/*************************************
* Main admin page. Lists all boxes.
*************************************/

$wpcb =  WPCB_Admin::get_instance();
$wpcb_tracking = WPCB_Tracker::get_instance();

if(isset($_GET['duplicate'])){
    $dupli_box_id = $_GET['duplicate'];
    $wpcb_duplicate_created = $wpcb->wpcb_duplicate_box($dupli_box_id);
    if($wpcb_duplicate_created == true){
        echo "<div class='updated'><p>". __('Successfully Duplicated!', 'wp-conversion-boxes') ."</p></div>";
    }
    else{
        echo "<div class='error'><p>". __('Box has not been duplicated. There was some error.', 'wp-conversion-boxes') ."</p></div>";
    }
}

?>

<div class="wrap">
    <h2><?php echo esc_html( get_admin_page_title() ); ?> <a href='<?php echo admin_url( 'admin.php?page=' . $this->wpcb_edit_slug ); ?>' class="add-new-h2"><?php _e('Add New', 'wp-conversion-boxes'); ?></a></h2>
    
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
                    $box_name = $wpcb->get_box_name($box_id);;
            ?>
                    <i></i>
                    <div class='postbox'>
                        <h3><?php echo $box_name; ?> : <?php _e('Top Performing Posts/Pages', 'wp-conversion-boxes'); ?></h3>
                        <div class='inside'>
                            <p><?php _e('Top performing posts and pages on your site.', 'wp-conversion-boxes'); ?></p>
                            <?php $wpcb_tracking->visit_details($box_id); ?>
                            <a class="wpcb_load_more_stats"><?php _e('Load More Data...', 'wp-conversion-boxes'); ?></a>
                        </div>
                    </div>

                    <div class='postbox opaque6'>
                        <h3><?php echo $box_name; ?> : <?php _e('Traffic and Conversion Graph', 'wp-conversion-boxes'); echo $wpcb->upgrade_to_pro(); ?></h3>
                        <div class='inside' style="height: 500px;">
                            <div class='wpcb_stats_filters'>
                                <form method="POST" action="" style="display: inline-block;">
                                    <label><i class="fa fa-calendar"></i> <?php _e('Date Range:', 'wp-conversion-boxes'); ?> <input disabled type="text" style="width: 200px" name="wpcb_date_range" id="wpcb_date_range" class="form-control" value="03/18/2013 - 03/23/2013" /></label>
                                </form>
                            </div>
                            <div id="placeholder" class="demo-placeholder" style="width: 90%; height: 270px; margin: 30px; padding: 0px; position: relative;"></div>
                            <p id="choices" style="line-height:30px; padding-bottom:6px; text-align: center;"></p>
                            <div id="overview" style="margin: 0 auto; width: 60%; height: 50px; padding: 0px; position: relative;"></div>
                            <p class="wpcb_help_block" style="text-align: center;"><?php _e('Overview (Zoom in/out of the graph)', 'wp-conversion-boxes'); ?></p>
                            <script>
                                var d = {
                                    data: [[1405468800000,10],[1405555200000,2],[1405641600000,15],[1405728000000,11],[1405814400000,32],[1405900800000,25],[1405987200000,45],[1406073600000,35],], 
                                    label: "<?php _e('Unique Visitors', 'wp-conversion-boxes'); ?>", 
                                    color: "#FFC200"
                                };
                                var d2 = {
                                    data: [[1405468800000,23],[1405555200000,6],[1405641600000,19],[1405728000000,16],[1405814400000,35],[1405900800000,31],[1405987200000,56],[1406073600000,43],], 
                                    label: "<?php _e('Pageviews', 'wp-conversion-boxes'); ?>",
                                    color: "#FF6820"
                                };
                                var d3 = {
                                    data: [[1405468800000,8],[1405555200000,1],[1405641600000,6],[1405728000000,6],[1405814400000,15],[1405900800000,19],[1405987200000,26],[1406073600000,20],],
                                    label: "<?php _e('Box Views', 'wp-conversion-boxes'); ?>",
                                    color: "#1180A7"
                                };
                                var d4 = {
                                    data: [[1405468800000,2],[1405555200000,1],[1405641600000,2],[1405728000000,1],[1405814400000,3],[1405900800000,0],[1405987200000,2],[1406073600000,5],],
                                    label: "<?php _e('Conversions', 'wp-conversion-boxes'); ?>",
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
                                            var month = date.getMonth() + 1;
                                            var day = date.getDate();
                                            var hour = date.getHours();
                                            var x = month + "/" + day + "/" + date.getFullYear();
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

                                choiceContainer.find("input").click(plotAccordingToChoices);

                                function plotAccordingToChoices() {
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
                            </script>
                        </div>
                    </div>
            <?php
                }
            ?>    
            </div>
    </div> 

</div>