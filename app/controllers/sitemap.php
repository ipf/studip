<?php
/*
 * SitemapController - Shows a global sitemap for all available pages
 *
 * This program is free software; you can redistribute it and/or
 * modify it under the terms of the GNU General Public License as
 * published by the Free Software Foundation; either version 2 of
 * the License, or (at your option) any later version.
 *
 * @author      Michael Riehemann <michael.riehemann@uni-oldenburg.de>
 * @license     http://www.gnu.org/licenses/gpl-2.0.html GPL version 2
 * @category    Stud.IP
 * @since       Stud.IP version 2.0
 */

require_once 'app/controllers/authenticated_controller.php';

/**
 * The sitemap is only visible for logged in users, because the sitemap depends
 * on the permissions of the user. It creates two new StudipNavigation Objects
 * to display the main navigation (toolbar) and the quick-links (subnavigation)
 *
 */
class SitemapController extends AuthenticatedController
{
    /**
     * The only main method, loads the navigation object and displays it.
     */
    public function index_action()
    {
        PageLayout::setTitle(_('Sitemap'));

        $this->navigation = Navigation::getItem('/');
        $this->quicklinks = Navigation::getItem('/links');
    }
}
