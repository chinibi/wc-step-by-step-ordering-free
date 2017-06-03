<?php

/**
 *  Step-By-Step Plugin Deactivation Script
 */

// Remove cron jobs
wp_clear_scheduled_hook( 'sbs_daily_event' );
