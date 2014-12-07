<?php
    global $wpdb;
    $wpcb_tracking = WPCB_Tracker::get_instance();
    $wpcb_tbl_name = $this->wpcb_boxes_table;
    $results = $wpdb->get_results("SELECT id,box_name,box_status,box_type,test_enabled,test_with FROM $wpcb_tbl_name ORDER BY id");
    $result_count = count($results);
    if($result_count != 0){
        $count = 1;
        ?>
        <table class="wp-list-table widefat fixed posts" style="clear: none;">
            <thead>
                <tr>
                    <?php if(get_option('wpcb_ga_tracking') == 0){ ?>
                    <th width="30%"><?php _e('WP Conversion Boxes Name','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Unique Visitors','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Pageviews','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Box Views','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Conversions','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Conversion Rate','wp-conversion-boxes-pro'); ?></th>
                    <?php } else { ?>
                    <th><?php _e('WP Conversion Boxes Name','wp-conversion-boxes-pro'); ?></th>
                    <th><?php _e('Box Options','wp-conversion-boxes-pro'); ?></th>
                    <?php } ?>
                </tr>
            </thead>

            <tfoot>
                <tr>
                    <?php if(get_option('wpcb_ga_tracking') == 0){ ?>
                    <th width="30%"><?php _e('WP Conversion Boxes Name','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Unique Visitors','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Pageviews','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Box Views','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Conversions','wp-conversion-boxes-pro'); ?></th>
                    <th style="text-align: center;"><?php _e('Conversion Rate','wp-conversion-boxes-pro'); ?></th>
                    <?php } else { ?>
                    <th><?php _e('WP Conversion Boxes Name','wp-conversion-boxes-pro'); ?></th>
                    <th><?php _e('Box Options','wp-conversion-boxes-pro'); ?></th>
                    <?php } ?>
                </tr>
            </tfoot>

            <tbody id="the-list">
            <?php
            foreach ($results as $result){
                $id = $result->id;
                $name = stripcslashes($result->box_name);
                $box_status = $result->box_status;
                $box_type = $result->box_type;
                $conversion_type = ($box_type == 1 || $box_type == 2) ? "<span class='wpcb_conversion_type'>". __('Optins','wp-conversion-boxes-pro') . "</span>" : "<span class='wpcb_conversion_type'>". __('Clicks','wp-conversion-boxes-pro') . "</span>";
                $test_enabled = $result->test_enabled;
                $test_with = $result->test_with;
                $pageviews = $wpcb_tracking->page_views($id);
                $uniquevisitors = $wpcb_tracking->unique_visitors($id);
                $uniqueboxviews = $wpcb_tracking->box_views($id);
                $conversions = $wpcb_tracking->get_conversions($id);
                $conversion_rate = ($uniqueboxviews != 0 && $conversions !=0) ? round(($conversions/$uniqueboxviews)*100, 2) : 0;

                if($box_status == 1){
                    $switch_class = 'switch_on';
                    $change_status_to = 0;
                }
                else{
                    $switch_class = 'switch_off';
                    $change_status_to = 1;
                }
                
                if($test_enabled == 1){
                    //Original
                    if($test_with != 0){
                        $wpcb_list = ++$count % 2 == 0 ? "<tr class='alternate wpcb-list-item-".$id."'>": "<tr class='wpcb-list-item-".$id."'>";
                        $wpcb_list .= "<td width='30%'>".$name;
                        
                        if(get_option('wpcb_ga_tracking') != 0){
                            $wpcb_list .= "</td><td>";
                        }
                        
                        $wpcb_list .= "<div class='wpcb-boxes-menu-toggle'><i class='fa fa-cog'></i> ". __('Options','wp-conversion-boxes-pro') ." <span class='fa fa-caret-down'></span></div><div class='wpcb-boxes-menu'><div class='wpcb-menu-left'><h3>". __('Customizations','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'>";
                        $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=1&id=' . $id )."' ><i class='fa fa-list'></i> ". __('Change Box Template','wp-conversion-boxes-pro') ."</a></li>";
                        $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=2&id=' . $id )."' ><i class='fa fa-paint-brush'></i> ". __('Customize Box Design','wp-conversion-boxes-pro') ."</a></li>";
                        $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=3&id=' . $id )."' ><i class='fa fa-wrench'></i> ". __('Box Settings','wp-conversion-boxes-pro') ."</a></li>";
                        $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&duplicate=' . $id )."' ><i class='fa fa-copy'></i> ". __('Duplicate This Box','wp-conversion-boxes-pro') ."</a></li>";
                        $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a><i class='fa fa-info-circle'></i> ". __('Box Status','wp-conversion-boxes') ."<div wpcb_id='".$id."' change_status_to='".$change_status_to."' class='wpcb_disable_switch ".$switch_class."'> <div class='switch_toggle'></div> <span class='switch_on'>". __('Enabled','wp-conversion-boxes') ."</span> <span class='switch_off'>". __('Disabled','wp-conversion-boxes') ."</span></div><div class='wpcb-disable-loading'><i class='fa fa-spinner fa-spin'></i></div></a></li>";
                        $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a class='wpcb_delete' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-trash'></i> ". __('Delete Box','wp-conversion-boxes-pro') ."</a></li></ul></div>";
                        $wpcb_list .= "<div class='wpcb-menu-right'>";
                        $wpcb_list .= "<h3>". __('Statistics','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'>";
                        
                        if(get_option('wpcb_ga_tracking') == 0){
                            $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&action=allstats&id=' . $id )."' ><i class='fa fa-line-chart'></i> ". __('Detailed Stats','wp-conversion-boxes-pro') ."</a></li>";
                            $wpcb_list .= ($pageviews != 0) ? "<li class='wpcb-boxes-menu-item'><a class='wpcb_flush' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-undo'></i> ". __('Reset Box Stats','wp-conversion-boxes-pro') ."</a></li>" : "";
                        }
                        else{
                            $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a target='_blank' href='https://www.google.com/analytics/web/' ><i class='fa fa-line-chart'></i> ". __('View Stats in Google Analytics','wp-conversion-boxes-pro') ."</a></li>";
                        }
                        
                        $wpcb_list .= "</ul><h3>". __('Shortcode','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item' style='padding: 5px 10px;'><input type='text' id='wpcb-shortcode-select' value='[wpcb id=\"".$id."\"]' ></li>";                                    
                        $wpcb_list .= "</ul></div></div></td>";
                        
                        if(get_option('wpcb_ga_tracking') == 0){
                            $wpcb_list .= "<td id='wpcb_unique_visitors'>".$uniquevisitors."</td>";
                            $wpcb_list .= "<td id='wpcb_pageviews'>".$pageviews."</td>";
                            $wpcb_list .= "<td id='wpcb_box_views'>".$uniqueboxviews."</td>";
                            $wpcb_list .= "<td id='wpcb_ctr_optins'>".$conversions.$conversion_type."</td>";
                            $wpcb_list .= "<td id='wpcb_ctr_optins_percent'>".$conversion_rate."%</td>";
                        }
                        
                        $wpcb_list .= "</tr>";
                        echo $wpcb_list;
                        $results1 = $wpdb->get_row("SELECT id,box_name,test_enabled,test_with FROM $wpcb_tbl_name WHERE id = $test_with");
                        $id = $results1->id;
                        $name = stripcslashes($results1->box_name);
                        $test_enabled = $results1->test_enabled;
                        $test_with = $results1->test_with;
                        $pageviews = $wpcb_tracking->page_views($id);
                        $uniquevisitors = $wpcb_tracking->unique_visitors($id);
                        $uniqueboxviews = $wpcb_tracking->box_views($id);
                        $conversions = $wpcb_tracking->get_conversions($id);
                        $conversion_rate = ($uniqueboxviews != 0 && $conversions !=0) ? round(($conversions/$uniqueboxviews)*100, 2) : 0;
                        $wpcb_list_2 = $count % 2 == 0 ? "<tr class='wpcb-list-test-variant alternate wpcb-list-item-".$id."'>": "<tr class='wpcb-list-test-variant wpcb-list-item-".$id."'>";
                        $wpcb_list_2 .= "<td width='30%' style='padding-left: 30px;'>".$name;
                        
                        if(get_option('wpcb_ga_tracking') != 0){
                            $wpcb_list_2 .= "</td><td>";
                        }
                        
                        $wpcb_list_2 .= "<div class='wpcb-boxes-menu-toggle'><i class='fa fa-cog'></i> ". __('Options','wp-conversion-boxes-pro') ." <span class='fa fa-caret-down'></span></div><div class='wpcb-boxes-menu'><div class='wpcb-menu-left'><h3>". __('Customizations','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'>";
                        $wpcb_list_2 .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=1&id=' . $id )."' ><i class='fa fa-list'></i> ". __('Change Box Template','wp-conversion-boxes-pro') ."</a></li>";
                        $wpcb_list_2 .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=2&id=' . $id )."' ><i class='fa fa-paint-brush'></i> ". __('Customize Box Design','wp-conversion-boxes-pro') ."</a></li>";
                        $wpcb_list_2 .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=3&id=' . $id )."' ><i class='fa fa-wrench'></i> ". __('Box Settings','wp-conversion-boxes-pro') ."</a></li>";
                        $wpcb_list_2 .= "<li class='wpcb-boxes-menu-item'><a class='wpcb_delete' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-trash'></i> ". __('Delete Box','wp-conversion-boxes-pro') ."</a></li></ul></div>";
                        $wpcb_list_2 .= "<div class='wpcb-menu-right'>";
                        $wpcb_list_2 .= "<h3>". __('Statistics','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'>";
                        
                        if(get_option('wpcb_ga_tracking') == 0){
                            $wpcb_list_2 .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&action=allstats&id=' . $id )."' ><i class='fa fa-line-chart'></i> ". __('Detailed Stats','wp-conversion-boxes-pro') ."</a></li>";
                            $wpcb_list_2 .= ($pageviews != 0) ? "<li class='wpcb-boxes-menu-item'><a class='wpcb_flush' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-undo'></i> ". __('Reset Box Stats','wp-conversion-boxes-pro') ."</a></li>" : "";
                        }
                        else{
                            $wpcb_list_2 .= "<li class='wpcb-boxes-menu-item'><a target='_blank' href='https://www.google.com/analytics/web/' ><i class='fa fa-line-chart'></i> ". __('View Stats in Google Analytics','wp-conversion-boxes-pro') ."</a></li>";
                        }
                        
                        $wpcb_list_2 .= "</ul></div></div></td>";
                        
                        if(get_option('wpcb_ga_tracking') == 0){
                            $wpcb_list_2 .= "<td id='wpcb_unique_visitors'>".$uniquevisitors."</td>";
                            $wpcb_list_2 .= "<td id='wpcb_pageviews'>".$pageviews."</td>";
                            $wpcb_list_2 .= "<td id='wpcb_box_views'>".$uniqueboxviews."</td>";
                            $wpcb_list_2 .= "<td id='wpcb_ctr_optins'>".$conversions.$conversion_type."</td>";
                            $wpcb_list_2 .= "<td id='wpcb_ctr_optins_percent'>".$conversion_rate."%</td>";
                        }
                        $wpcb_list_2 .= "</tr>";
                        echo $wpcb_list_2;

                    }
                    //If $result is Variant B
                    else{

                    }
                }
                else{
                    $wpcb_list = ++$count % 2 == 0 ? "<tr class='alternate wpcb-list-item-".$id."'>": "<tr class='wpcb-list-item-".$id."'>";
                    $wpcb_list .= "<td width='30%'>".$name;
                    
                    if(get_option('wpcb_ga_tracking') != 0){
                        $wpcb_list .= "</td><td>";
                    }
                    
                    $wpcb_list .= "<div class='wpcb-boxes-menu-toggle'><i class='fa fa-cog'></i> ". __('Options','wp-conversion-boxes-pro') ." <span class='fa fa-caret-down'></span></div><div class='wpcb-boxes-menu'><div class='wpcb-menu-left'><h3>". __('Customizations','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'>";
                    $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=1&id=' . $id )."' ><i class='fa fa-list'></i> ". __('Change Box Template','wp-conversion-boxes-pro') ."</a></li>";
                    $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=2&id=' . $id )."' ><i class='fa fa-paint-brush'></i> ". __('Customize Box Design','wp-conversion-boxes-pro') ."</a></li>";
                    $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_edit_slug .'&step=3&id=' . $id )."' ><i class='fa fa-wrench'></i> ". __('Box Settings','wp-conversion-boxes-pro') ."</a></li>";
                    $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&duplicate=' . $id )."' ><i class='fa fa-copy'></i> ". __('Duplicate This Box','wp-conversion-boxes-pro') ."</a></li>";
                    $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a><i class='fa fa-info-circle'></i> ". __('Box Status','wp-conversion-boxes') ."<div wpcb_id='".$id."' change_status_to='".$change_status_to."' class='wpcb_disable_switch ".$switch_class."'> <div class='switch_toggle'></div> <span class='switch_on'>". __('Enabled','wp-conversion-boxes') ."</span> <span class='switch_off'>". __('Disabled','wp-conversion-boxes') ."</span></div><div class='wpcb-disable-loading'><i class='fa fa-spinner fa-spin'></i></div></a></li>";
                    $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a class='wpcb_delete' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-trash'></i> ". __('Delete Box','wp-conversion-boxes-pro') ."</a></li></ul></div>";
                    $wpcb_list .= "<div class='wpcb-menu-right'>";
                    $wpcb_list .= "<h3>". __('A/B Test','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&create-ab-test=' . $id )."'><i class='fa fa-random'></i> ". __('A/B Test This Box','wp-conversion-boxes-pro') ."</a></li></ul>";
                    $wpcb_list .= "<h3>". __('Statistics','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'>";
                    
                    if(get_option('wpcb_ga_tracking') == 0){
                        $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a href='".admin_url( 'admin.php?page=' . $this->wpcb_main_slug .'&action=allstats&id=' . $id )."' ><i class='fa fa-line-chart'></i> ". __('Detailed Stats','wp-conversion-boxes-pro') ."</a></li>";
                        $wpcb_list .= ($pageviews != 0) ? "<li class='wpcb-boxes-menu-item'><a class='wpcb_flush' style='color: #a00;' href='' wpcb_id='".$id."'><i class='fa fa-undo'></i> ". __('Reset Box Stats','wp-conversion-boxes-pro') ."</a></li>" : "";
                    }
                    else{
                        $wpcb_list .= "<li class='wpcb-boxes-menu-item'><a target='_blank' href='https://www.google.com/analytics/web/' ><i class='fa fa-line-chart'></i> ". __('View Stats in Google Analytics','wp-conversion-boxes-pro') ."</a></li>";
                    }
                    
                    $wpcb_list .= "</ul><h3>". __('Shortcode','wp-conversion-boxes-pro') ."</h3><ul class='wpcb-menu-ul'><li class='wpcb-boxes-menu-item' style='padding: 5px 10px;'><input type='text' id='wpcb-shortcode-select' value='[wpcb id=\"".$id."\"]' ></li>";
                    $wpcb_list .= "</ul></div></div></td>";
                    if(get_option('wpcb_ga_tracking') == 0){
                        $wpcb_list .= "<td id='wpcb_unique_visitors'>".$uniquevisitors."</td>";
                        $wpcb_list .= "<td id='wpcb_pageviews'>".$pageviews."</td>";
                        $wpcb_list .= "<td id='wpcb_box_views'>".$uniqueboxviews."</td>";
                        $wpcb_list .= "<td id='wpcb_ctr_optins'>".$conversions.$conversion_type."</td>";
                        $wpcb_list .= "<td id='wpcb_ctr_optins_percent'>".$conversion_rate."%</td>";
                    }
                    $wpcb_list .= "</tr>";
                    echo $wpcb_list;
                }
            }
            ?>
            </tbody>
        </table>
        <?php
    }
    else{
        echo sprintf( __( 'No boxes found. Please <a href="%s">click here</a> to create a new WP Conversion Box.','wp-conversion-boxes-pro'), admin_url( 'admin.php?page=' . $this->wpcb_edit_slug));
    }
    