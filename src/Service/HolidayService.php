<?php

namespace App\Service;

use App\Entity\Holiday;
use App\Repository\HolidayRepository;
use Exception;

class HolidayService
{
    public function __construct( private readonly HolidayRepository $holidayRepository )
    {
    }

    public function getAll()
    {
        return $this->holidayRepository->findAll();
    }

    /**
     * @throws Exception
     */
    public function addHoliday( Holiday $holiday ) : void
    {
        // check if holiday already exists (startDate and endDate is between existing holiday)
        $existingHoliday = $this->holidayRepository->findHolidayBetweenDates( $holiday->getStartDate(), $holiday->getEndDate() );

        if ( $existingHoliday ) {
            throw new Exception( 'Holiday already exists' );
        }

        if ( $holiday->getStartDate() > $holiday->getEndDate() ) {
            throw new Exception( 'Start date must be before end date' );
        }

        $this->holidayRepository->save( $holiday, true );
    }

    /**
     * @throws Exception
     */
    public function updateHoliday( Holiday $holiday )
    {
        // check if holiday already exists (startDate and endDate is between existing holiday)
        /** @var Holiday $existingHoliday */
        $existingHoliday = $this->holidayRepository->findHolidayBetweenDates( $holiday->getStartDate(), $holiday->getEndDate() );


        if ( $existingHoliday && $existingHoliday[0]->getId() !== $holiday->getId() ) {
            throw new Exception( 'Holiday already exists' );
        }

        if ( $holiday->getStartDate() > $holiday->getEndDate() ) {
            throw new Exception( 'Start date must be before end date' );
        }

        $this->holidayRepository->save( $holiday, true );
    }
}