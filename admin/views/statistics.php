<?php

/*************************************
* Statistics Page
*************************************/

$wpcb = WPCB_Admin::get_instance();
$wpcb_public = WPCB_Public::get_instance();
$wpcb_tracking = WPCB_Tracker::get_instance();

?>

<div class="wrap wpcb_main">

    <h2><?php echo esc_html( get_admin_page_title() ); ?></h2>
   
    <div id="poststuff" class="metabox-holder has-right-sidebar">    

        <div class="inner-sidebar" id="side-info-column">
            <?php $wpcb->wpcb_sidebar(); ?>
        </div>

        <div id="post-body-content">
            
            <?php    
                if(!isset($_GET['allstats']) && !isset($_GET['id'])){
            ?>
            
                <div class='postbox'>
                    <h3>Conversion Boxes Stats</h3>
                    <div class='inside'>
                        <?php
                            global $wpdb;
                            $wpcb_tbl_name = $this->wpcb_boxes_table;
                            $results = $wpdb->get_results("SELECT id,box_name FROM $wpcb_tbl_name ORDER BY id DESC");
                            $result_count = count($results);
                            if($result_count != 0){
                                $count = 1;
                                ?>
                                <table class="wp-list-table widefat fixed posts" style="clear: none;">
                                    <thead>
                                        <tr>
                                            <th width="25%">WP Conversion Boxes Name</th>
                                            <th style="text-align: center;">Unique Visitors</th>
                                            <th style="text-align: center;">Pageviews</th>
                                            <th style="text-align: center;">Box Views</th>
                                            <th style="text-align: center;">Conversion</th>
                                            <th style="text-align: center;">Conversion Rate</th>
                                        </tr>
                                    </thead>

                                    <tbody id="the-list">
                                    <?php
                                    foreach ($results as $result){
                                        $id = $result->id;
                                        $name = $result->box_name;
                                        $pageviews = $wpcb_tracking->page_views($id);
                                        $uniquevisitors = $wpcb_tracking->unique_visitors($id);
                                        $uniqueboxviews = $wpcb_tracking->box_views($id);
                                        $conversions = $wpcb_tracking->get_conversions($id);
                                        $conversion_rate = ($uniqueboxviews != 0 && $conversions !=0) ? round(($conversions/$uniqueboxviews)*100, 2) : 0;

                                        $wpcb_list = ++$count % 2 == 0 ? "<tr class='alternate wpcb-list-item-".$id."'>": "<tr class='wpcb-list-item-".$id."'>";
                                        $wpcb_list .= "<td>".$name."<div class='row-actions'>";
                                        $wpcb_list .= "<a href='".admin_url( 'admin.php?page=' . $this->wpcb_stats_slug .'&action=allstats&id=' . $id )."' >Detailed Stats</a>";
                                        $wpcb_list .= ($pageviews != 0) ? " | <a class='wpcb_flush' style='color: #a00;' href='' wpcb_id='".$id."'>Flush Stats</a>" : "";
                                        $wpcb_list .= "</div></td>";
                                        $wpcb_list .= "<td id='wpcb_unique_visitors'>".$uniquevisitors."</td>";
                                        $wpcb_list .= "<td id='wpcb_pageviews'>".$pageviews."</td>";
                                        $wpcb_list .= "<td id='wpcb_box_views'>".$uniqueboxviews."</td>";
                                        $wpcb_list .= "<td id='wpcb_ctr_optins'>".$conversions."</td>";
                                        $wpcb_list .= "<td id='wpcb_ctr_optins_percent'>".$conversion_rate."%</td>";
                                        $wpcb_list .= "</tr>";
                                        echo $wpcb_list;
                                    }
                                    ?>
                                    </tbody>
                                </table>
                                <?php
                            }
                            else{
                                echo "No boxes found. Please <a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug)."'>click here</a> to create a new WP Conversion Box.";
                            }
                        ?>
                    </div>
                </div>
                <div class="postbox opaque6">
                    <h3>A/B Tests<?php echo $wpcb->upgrade_to_pro(); ?></h3>
                    <div class='inside'>
                        <table class="wp-list-table widefat fixed posts" style="clear: none;">
                            <thead>
                                <tr>
                                    <th width="25%">A/B Test Name</th>
                                    <th style="text-align: center;">Unique Visitors</th>
                                    <th style="text-align: center;">Pageviews</th>                                
                                    <th style="text-align: center;">Box Views</th>
                                    <th style="text-align: center;">Conversion</th>
                                    <th style="text-align: center;">Conversion Rate</th>
                                </tr>
                            </thead>

                            <tbody id="the-list">
                                <tr class="wpcb_split_test_name">
                                    <td>My New Split Test #1</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="wpcb_split_test_box_name">
                                    <td>My Box #1</td>
                                    <td id="wpcb_unique_visitors">401</td>
                                    <td id="wpcb_pageviews">533</td>                                
                                    <td id="wpcb_box_views">370</td>
                                    <td id="wpcb_ctr_optins">21</td>
                                    <td id="wpcb_ctr_optins_percent">5.67%</td>
                                </tr>
                                <tr class="wpcb_split_test_box_name">
                                    <td>My Box #2</td>
                                    <td id="wpcb_unique_visitors">390</td>
                                    <td id="wpcb_pageviews">532</td>                                
                                    <td id="wpcb_box_views">362</td>
                                    <td id="wpcb_ctr_optins">38</td>
                                    <td id="wpcb_ctr_optins_percent">10.50%</td>
                                </tr>
                                <tr class="wpcb_split_test_name alternate">
                                    <td>My New Split Test #2</td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                </tr>
                                <tr class="wpcb_split_test_box_name alternate">
                                    <td>My Box #3</td>
                                    <td id="wpcb_unique_visitors">156</td>
                                    <td id="wpcb_pageviews">221</td>                                
                                    <td id="wpcb_box_views">120</td>
                                    <td id="wpcb_ctr_optins">19</td>
                                    <td id="wpcb_ctr_optins_percent">15.83%</td>
                                </tr>
                                <tr class="wpcb_split_test_box_name alternate">
                                    <td>My Box #4</td>
                                    <td id="wpcb_unique_visitors">159</td>
                                    <td id="wpcb_pageviews">222</td>                                
                                    <td id="wpcb_box_views">135</td>
                                    <td id="wpcb_ctr_optins">11</td>
                                    <td id="wpcb_ctr_optins_percent">8.15%</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

            
            <?php
                } //IF ends here
                else{ 
                    $box_id = $_GET['id'];
                    $action = $_GET['allstats'];
                    $startdate = date('m/d/Y', strtotime('-30 days'));
                    $enddate = date("m/d/Y");
            ?>
                <div class='postbox'>
                    <h3><?= $wpcb->get_box_name($box_id); ?> : Top Performing Posts/Pages</h3>
                    <div class='inside'>
                        <p>Top performing posts and pages on your site.</p>
                        <?php $wpcb_tracking->visit_details($box_id); ?>
                        <a class="wpcb_load_more_stats">Load More Data...</a>
                    </div>
                </div>
            
                <div class='postbox opaque6'>
                    <h3><?= $wpcb->get_box_name($box_id); ?> : Traffic and Conversion Graph<?php echo $wpcb->upgrade_to_pro(); ?></h3>
                    <div class='inside' style="height: 500px;">
                        <div class='wpcb_stats_filters'>
                            <span class='wpcb_stats_freq_span'>
                                <span class='wpcb_stats_freq' href='<?= admin_url( 'admin.php?page=' . $this->wpcb_stats_slug .'&action=allstats&id=' . $box_id . '&freq=hourly'); ?>'>Hourly</span>
                                <span class='wpcb_stats_freq' href='<?= admin_url( 'admin.php?page=' . $this->wpcb_stats_slug .'&action=allstats&id=' . $box_id . '&freq=days'); ?>'>Day</span>
                                <span class='wpcb_stats_freq' href='<?= admin_url( 'admin.php?page=' . $this->wpcb_stats_slug .'&action=allstats&id=' . $box_id . '&freq=weeks'); ?>'>Week</span>
                                <span class='wpcb_stats_freq' href='<?= admin_url( 'admin.php?page=' . $this->wpcb_stats_slug .'&action=allstats&id=' . $box_id . '&freq=months'); ?>'>Month</span>
                            </span>
                            <form method="POST" action="" style="display: inline-block;">
                                <label for="datefrom">From: <input type="text" id="datefrom" name="datefrom" value="<?= $startdate; ?>" class="wpcb_datepicker" disabled/></label>
                                <label for="dateto">To: <input type="text" id="dateto" name="dateto" value="<?= $enddate; ?>" class="wpcb_datepicker" disabled/></label>
                                <input type="submit" value="Go" disabled>
                            </form>
                        </div>
                        <div id="placeholder" class="demo-placeholder" style="width: 90%; height: 270px; margin: 30px; padding: 0px; position: relative;"></div>
                        <p id="choices" style="line-height:30px; padding-bottom:6px; text-align: center;"></p>
                        <div id="overview" style="margin: 0 auto; width: 60%; height: 50px; padding: 0px; position: relative;"></div>
                        <p class="wpcb_help_block" style="text-align: center;">Overview (Zoom in/out of the graph)</p>
                        <script>
                            var d = {
                                data: [[1405468800000,10],[1405555200000,2],[1405641600000,15],[1405728000000,11],[1405814400000,32],[1405900800000,25],[1405987200000,45],[1406073600000,35],], 
                                label: "Unique Visitors", 
                                color: "#FFC200"
                            };
                            var d2 = {
                                data: [[1405468800000,23],[1405555200000,6],[1405641600000,19],[1405728000000,16],[1405814400000,35],[1405900800000,31],[1405987200000,56],[1406073600000,43],], 
                                label: "Pageviews",
                                color: "#FF6820"
                            };
                            var d3 = {
                                data: [[1405468800000,8],[1405555200000,1],[1405641600000,6],[1405728000000,6],[1405814400000,15],[1405900800000,19],[1405987200000,26],[1406073600000,20],],
                                label: "Box Views",
                                color: "#1180A7"
                            };
                            var d4 = {
                                data: [[1405468800000,2],[1405555200000,1],[1405641600000,2],[1405728000000,1],[1405814400000,3],[1405900800000,0],[1405987200000,2],[1406073600000,5],],
                                label: "Conversions",
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
                <?php } // End else ?>
        </div>
        
    </div>
    
</div>    

