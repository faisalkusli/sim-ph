<?php

/**
 * Global Helper Functions untuk Workflow Tracking
 * 
 * File ini di-load otomatis via composer.json
 */

use App\Helpers\WorkflowHelper;

if (!function_exists('getStatusColor')) {
    /**
     * Get status color untuk timeline marker
     */
    function getStatusColor($statusLog)
    {
        return WorkflowHelper::getStatusColor($statusLog);
    }
}

if (!function_exists('getStatusIcon')) {
    /**
     * Get status icon untuk timeline marker
     */
    function getStatusIcon($statusLog)
    {
        return WorkflowHelper::getStatusIcon($statusLog);
    }
}

if (!function_exists('getStatusBadge')) {
    /**
     * Get status badge HTML
     */
    function getStatusBadge($status, $type = 'surat')
    {
        return WorkflowHelper::getStatusBadge($status, $type);
    }
}

if (!function_exists('canValidateSurat')) {
    /**
     * Check apakah user bisa validate surat
     */
    function canValidateSurat($user)
    {
        return WorkflowHelper::canValidateSurat($user);
    }
}

if (!function_exists('canForwardDisposisi')) {
    /**
     * Check apakah user bisa forward disposisi
     */
    function canForwardDisposisi($user)
    {
        return WorkflowHelper::canForwardDisposisi($user);
    }
}

if (!function_exists('canVerifyDisposisi')) {
    /**
     * Check apakah user bisa verify disposisi
     */
    function canVerifyDisposisi($user)
    {
        return WorkflowHelper::canVerifyDisposisi($user);
    }
}

if (!function_exists('canNaikTurunBupati')) {
    /**
     * Check apakah user bisa naik/turun bupati
     */
    function canNaikTurunBupati($user)
    {
        return WorkflowHelper::canNaikTurunBupati($user);
    }
}
