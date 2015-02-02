<?php
/**
 * This software is governed by the CeCILL-B license. If a copy of this license
 * is not distributed with this file, you can obtain one at
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-en.txt
 *
 * Authors of STUdS (initial project): Guilhem BORGHESI (borghesi@unistra.fr) and Raphaël DROZ
 * Authors of Framadate/OpenSondate: Framasoft (https://github.com/framasoft)
 *
 * =============================
 *
 * Ce logiciel est régi par la licence CeCILL-B. Si une copie de cette licence
 * ne se trouve pas avec ce fichier vous pouvez l'obtenir sur
 * http://www.cecill.info/licences/Licence_CeCILL-B_V1-fr.txt
 *
 * Auteurs de STUdS (projet initial) : Guilhem BORGHESI (borghesi@unistra.fr) et Raphaël DROZ
 * Auteurs de Framadate/OpenSondage : Framasoft (https://github.com/framasoft)
 */
use Framadate\Services\LogService;
use Framadate\Services\PollService;
use Framadate\Services\InputService;
use Framadate\Services\MailService;
use Framadate\Message;
use Framadate\Utils;

include_once __DIR__ . '/app/inc/init.php';

ob_start();

/* Variables */
/* --------- */

$poll_id = null;
$poll = null;

/* Services */
/*----------*/

$logService = new LogService();
$pollService = new PollService($connect, $logService);

/* PAGE */
/* ---- */

if (!empty($_GET['poll'])) {
    $poll_id = filter_input(INPUT_GET, 'poll', FILTER_VALIDATE_REGEXP, ['options' => ['regexp' => '/^[a-z0-9]+$/']]);
    $poll = $pollService->findById($poll_id);
}

if (!$poll) {
    $smarty->assign('error', _('This poll doesn\'t exist !'));
    $smarty->display('error.tpl');
    exit;
}


$slots = $pollService->allSlotsByPollId($poll_id);
$votes = $pollService->allVotesByPollId($poll_id);

// CSV header
if ($poll->format === 'D') {
    $titles_line = ',';
    $moments_line = ',';
    foreach ($slots as $slot) {
        $title = Utils::csvEscape(strftime($date_format['txt_date'], $slot->title));
        $moments = explode(',', $slot->moments);

        $titles_line .= str_repeat($title . ',', count($moments));
        $moments_line .= implode(',', array_map('\Framadate\Utils::csvEscape', $moments)) . ',';
    }
    echo $titles_line . "\r\n";
    echo $moments_line . "\r\n";
} else {
    echo ',';
    foreach ($slots as $slot) {
        echo Utils::markdown($slot->title, true) . ',';
    }
    echo "\r\n";
}
// END - CSV header

// Vote lines
foreach ($votes as $vote) {
    echo Utils::csvEscape($vote->name) . ',';
    $choices = str_split($vote->choices);
    foreach ($choices as $choice) {
        switch ($choice) {
            case 0:
                $text = _('No');
                break;
            case 1:
                $text = _('Ifneedbe');
                break;
            case 2:
                $text = _('Yes');
                break;
            default:
                $text = 'unkown';
        }
        echo Utils::csvEscape($text);
        echo ',';
    }
    echo "\r\n";
}
// END - Vote lines

// HTTP headers
$content = ob_get_clean();
$filesize = strlen($content);
$filename = Utils::cleanFilename($poll->title) . '.csv';

header('Content-Type: text/csv; charset=utf-8');
header('Content-Length: ' . $filesize);
header('Content-Disposition: attachment; filename="' . $filename . '"');
header('Cache-Control: max-age=10');
// END - HTTP headers

echo $content;
