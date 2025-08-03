<?php

require_once 'services/PublicHolidays.php';
require_once 'services/ScheduleComputer.php';
require_once 'services/ScheduleDay.php';

$monthlyPlanning = [
    ['date' => '2025-05-01', 'workedHours' => 8], // holiday
    ['date' => '2025-05-02', 'workedHours' => null],
    ['date' => '2025-05-03', 'workedHours' => 0],
    ['date' => '2025-05-04', 'workedHours' => 3], // sunday
    ['date' => '2025-05-05', 'workedHours' => 9999999],
    ['date' => '2025-05-06'],
    ['date' => '2025-05-07'],
    ['date' => '2025-05-08', 'workedHours' => 4], // holiday
    ['date' => '2025-05-09', 'workedHours' => 8],
    ['date' => '2025-05-10', 'workedHours' => 8],
    ['date' => '2025-05-11'],                     // sunday
    ['date' => '2025-05-12'],
    ['date' => '2025-05-13', 'workedHours' => 8],
    ['date' => '2025-05-14', 'workedHours' => 8],
    ['date' => '2025-05-15', 'workedHours' => 8],
    ['date' => '2025-05-16', 'workedHours' => 8],
    ['date' => '2025-05-17'],
    ['date' => '2025-05-18', 'workedHours' => 7], // sunday
    ['date' => '2025-05-19'],
    ['date' => '2025-05-20', 'workedHours' => 8],
    ['date' => '2025-05-21', 'workedHours' => 8],
    ['date' => '2025-05-22', 'workedHours' => 8],
    ['date' => '2025-05-23', 'workedHours' => 8],
    ['date' => '2025-05-24', 'workedHours' => 8],
    ['date' => '2025-05-25', 'workedHours' => 8], // sunday
    ['date' => '2025-05-26'],
    ['date' => '2025-05-27'],
    ['date' => '2025-05-28', 'workedHours' => 8],
    ['date' => '2025-05-29', 'workedHours' => 5], // holiday
    ['date' => '2025-05-30', 'workedHours' => 8],
    ['date' => '2025-05-31', 'workedHours' => 8],
];

$monthlyTotals = (new ScheduleComputer())->computeMonthlyTotals($monthlyPlanning);

$date = new DateTime('2025-05-01');
$formatter = new IntlDateFormatter('fr_FR', IntlDateFormatter::LONG, IntlDateFormatter::NONE, null, null, 'LLLL yyyy');
$month = $formatter->format($date);

echo <<<HTML
    <span>Totaux mensuels pour le mois de $month :</span>
    <br><br>
    <table border="1" cellpadding="5" cellspacing="0">
        <thead>
            <tr>
                <th>Type</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>Jours travaillés</td>
                <td>{$monthlyTotals['workedDaysCount']} j</td>
            </tr>
            <tr>
                <td>Heures travaillées</td>
                <td>{$monthlyTotals['workedHoursCount']} h</td>
            </tr>
            <tr>
                <td>Jours d'absence</td>
                <td>{$monthlyTotals['absenceDaysCount']} j</td>
            </tr>
            <tr>
                <td>Dimanches travaillés</td>
                <td>{$monthlyTotals['workedSundaysCount']} j</td>
            </tr>
            <tr>
                <td>Heures de dimanche travaillées</td>
                <td>{$monthlyTotals['workedSundayHoursCount']} h</td>
            </tr>
            <tr>
                <td>Jours fériés travaillés</td>
                <td>{$monthlyTotals['workedPublicHolidaysCount']} j</td>
            </tr>
            <tr>
                <td>Heures fériées travaillées</td>
                <td>{$monthlyTotals['workedPublicHolidaysHoursCount']} h</td>
            </tr>
        </tbody>
    </table>
HTML;
