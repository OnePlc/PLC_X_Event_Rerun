--
-- Add new tab
--
INSERT INTO `core_form_tab` (`Tab_ID`, `form`, `title`, `subtitle`, `icon`, `counter`, `sort_id`, `filter_check`, `filter_value`) VALUES
('event-rerun', 'event-single', 'Reruns', 'Event Reruns', 'fas fa-redo', '', '1', '', '');

--
-- Add new partial
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'partial', 'Reruns', 'event_rerun', 'event-rerun', 'event-single', 'col-md-12', '', '', '0', '1', '0', '', '', '');

--
-- add button
--
INSERT INTO `core_form_button` (`Button_ID`, `label`, `icon`, `title`, `href`, `class`, `append`, `form`, `mode`, `filter_check`, `filter_value`) VALUES
(NULL, 'Add Rerun', 'fas fa-redo', 'Add Rerun', '/event/rerun/add/##ID##', 'primary', '', 'event-view', 'link', '', ''),
(NULL, 'Save Rerun', 'fas fa-save', 'Save Rerun', '#', 'primary saveForm', '', 'eventrerun-single', 'link', '', '');

--
-- add history form
--
INSERT INTO `core_form` (`form_key`, `label`, `entity_class`, `entity_tbl_class`) VALUES
('eventrerun-single', 'Event Rerun', 'OnePlace\\Event\\Rerun\\Model\\Rerun', 'OnePlace\\Event\\Rerun\\Model\\RerunTable');

--
-- add form tab
--
INSERT INTO `core_form_tab` (`Tab_ID`, `form`, `title`, `subtitle`, `icon`, `counter`, `sort_id`, `filter_check`, `filter_value`) VALUES
('rerun-base', 'eventrerun-single', 'Rerun', 'Rerun Data', 'fas fa-redo', '', '1', '', '');

--
-- add address fields
--
INSERT INTO `core_form_field` (`Field_ID`, `type`, `label`, `fieldkey`, `tab`, `form`, `class`, `url_view`, `url_list`, `show_widget_left`, `allow_clear`, `readonly`, `tbl_cached_name`, `tbl_class`, `tbl_permission`) VALUES
(NULL, 'datetime', 'start Date', 'date_start', 'rerun-base', 'eventrerun-single', 'col-md-2', '', '', 0, 1, 0, '', '', ''),
(NULL, 'datetime', 'end Date', 'date_end', 'rerun-base', 'eventrerun-single', 'col-md-2', '', '', 0, 1, 0, '', '', ''),
(NULL, 'select', 'daily Event', 'is_daily_event_idfs', 'rerun-base', 'eventrerun-single', 'col-md-2', '', '/application/selectbool', 0, 1, 0, '', 'OnePlace\\BoolSelect', ''),
(NULL, 'hidden', 'Event', 'event_idfs', 'rerun-base', 'eventrerun-single', 'col-md-3', '', '/', '0', '1', '0', '', '', '');

--
-- permission add history
--
INSERT INTO `permission` (`permission_key`, `module`, `label`, `nav_label`, `nav_href`, `show_in_menu`) VALUES
('add', 'OnePlace\\Event\\Rerun\\Controller\\RerunController', 'Add Rerun', '', '', '0');