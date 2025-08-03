<?php

class ScheduleComputer {
    /** 
     * @param array{
     *      date: string,
     *      workedHours: int,
     * }[] $scheduleDays
     * @return array {
     *      workedDaysCount: int,
     *      workedHoursCount: int,
     *      absenceDaysCount: int,
     *      workedSundaysCount: int,
     *      workedSundayHoursCount: int,
     *      workedPublicHolidaysCount: int,
     *      workedPublicHolidaysHoursCount: int,
     * }
     */
    public function computeMonthlyTotals(array $scheduleDays): array
    {
        $totals = [
            'workedDaysCount' => 0,
            'workedHoursCount' => 0,
            'absenceDaysCount' => 0,
            'workedSundaysCount' => 0,
            'workedSundayHoursCount' => 0,
            'workedPublicHolidaysCount' => 0,
            'workedPublicHolidaysHoursCount' => 0,
        ];

        $year = null;
        if (!empty($scheduleDays)) {
            $year = (int) (new DateTime($scheduleDays[0]['date']))->format('Y');
        }
        $publicHolidays = new PublicHolidays($year ?? date('Y'));

        foreach ($scheduleDays as $scheduleDay) {
            $day = new ScheduleDay(
                $publicHolidays,
                $scheduleDay['date'],
                $scheduleDay['workedHours'] ?? 0,
            );
            
            if (!$day->isWorked()) {
                $totals['absenceDaysCount']++;
                continue;
            }

            $workedHours = $day->workedHours;

            $totals['workedDaysCount']++;
            $totals['workedHoursCount'] += $workedHours;
            
            $isSunday = $day->isSunday();
            $isPublicHoliday = $day->isPublicHoliday();

            if ($isSunday && $isPublicHoliday) {
                if ($workedHours > 4) {
                    $totals['workedPublicHolidaysCount']++;
                    $totals['workedPublicHolidaysHoursCount'] += $workedHours;
                } elseif ($workedHours > 3) {
                    /**
                     * Je pense que cette règle ($workedHours > 3) serait à implémenter
                     * mais j'aurais demandé confirmation à l'analyste.
                     * 
                     * En effet, il existe la règle de gestion suivante:
                     * En cas de dimanche férié, les heures de fériés
                     * sont prioritaires sur les heures de dimanche.
                     * 
                     * Donc si les heures travaillées sont exactement 4 heures
                     * alors ces heures ne seraient pas considérées comme fériées
                     * mais comme ordinaires (d'après la 1ere règle de gestion).
                     * 
                     * Et si elles sont ordinaires, elles ne seraient pas considérées
                     * comme des heures du dimanche, ce qui m'interpelle.
                     */
                    $totals['workedSundaysCount']++;
                    $totals['workedSundayHoursCount'] += $workedHours;
                }
            } elseif ($isPublicHoliday) {
                if ($workedHours > 4) {
                    $totals['workedPublicHolidaysCount']++;
                    $totals['workedPublicHolidaysHoursCount'] += $workedHours;
                }
            } elseif ($isSunday) {
                if ($workedHours > 3) {
                    $totals['workedSundaysCount']++;
                    $totals['workedSundayHoursCount'] += $workedHours;
                }
            }
        }

        return $totals;
    }
}
