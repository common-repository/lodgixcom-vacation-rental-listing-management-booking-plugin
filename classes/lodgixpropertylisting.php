<?php

class LodgixPropertyListing {

    function __construct(
        $property,
        $displayRatesDaily=true,
        $displayRatesWeekly=true,
        $displayRatesMonthly=true,
        $permalink='',
        $displayLowRate=true,
        $displayHighRate=true,
        $displayBedrooms=true,
        $showCategory=true,
        $displayMinStay=true
    ) {
        $this->property = $property;

        if (empty($permalink)) {
            $this->permalink = 'javascript:void(0)';
            $this->hrefBook = 'javascript:void(0)';
            $this->hrefMap = 'javascript:void(0)';
        } else {
            $this->permalink = $permalink;
            $this->hrefBook = $permalink . '#booking';
            $this->hrefMap = $permalink . '#map';
        }

        $lp = new LodgixServiceProperty($property);
        $this->minStay = $lp->minStay();
        if( $displayRatesDaily == false){
            $this->displayRatesDailyFrom = false;
        } else {
            $this->displayRatesDailyFrom = true;
        }
        if ($this->minStay[0] >= 7) {
            $displayRatesDaily = false;
        }
        if ($this->minStay[0] >= 30) {
            $displayRatesWeekly = false;
        }
        $this->ratesDaily = $displayRatesDaily ? $lp->ratesDaily() : null;
        $this->ratesDailyFrom = $lp->ratesDaily();
        $this->ratesWeekly = $displayRatesWeekly ? $lp->ratesWeekly(): null;
        $this->ratesMonthly = $displayRatesMonthly ? $lp->ratesMonthly() : null;

        $this->displayLowRate = $displayLowRate;
        $this->displayHighRate = $displayHighRate;
        $this->displayBedrooms = $displayBedrooms;
        $this->showCategory = $showCategory;
        $this->displayMinStay = $this->minStay[0] > 1 ? $displayMinStay : false;
    }

    function gridCell(
        $nofloat=true,
        $isNewRow=false
    ) {
        $html =
            '<div class="property_div_logix property' . ($nofloat ? ' nofloat' : '') . ($isNewRow ? ' new-row' : '') . '">
                    <div class="property_image">
                        <a class="property_overview_thumb" href="' . $this->permalink . '"><img border="0" src="' . $this->property->main_image_thumb . '" alt="'.$this->property->description.'"></a>
                    </div>
                    <ul>
                        <li class="property_title"><a href="' . $this->permalink . '">' . $this->property->description . '</a></li>
                        <li class="description"> ' . $this->property->description_long . '</li>';

        if ($this->ratesDaily) {
            if ($this->displayLowRate) {
                $html .=
                    '<li id="lodgix_daily_rates" class="ldgxGridRate ldgxGridRateDaily ldgxGridRateLow ldgxGridRateDailyLow"><strong>'
                    . LodgixTranslate::translate('Price Per Night')
                    . ':</strong> ' . LodgixTranslate::translate('from') . ' '
                    . $this->ratesDaily[0]
                    . '</li>';
            }
            if ($this->displayHighRate) {
                $html .=
                    '<li id="lodgix_daily_rates" class="ldgxGridRate ldgxGridRateDaily ldgxGridRateHigh ldgxGridRateDailyHigh"><strong>'
                    . LodgixTranslate::translate('Daily Rate')
                    . ':</strong> '
                    . $this->ratesDaily[0]
                    . ' - '
                    . $this->ratesDaily[1]
                    . '</li>';
            }
        }
        if ($this->ratesWeekly) {
            if ($this->displayLowRate) {
                $html .=
                    '<li id="lodgix_weekly_rates" class="ldgxGridRate ldgxGridRateWeekly ldgxGridRateLow ldgxGridRateWeeklyLow"><strong>'
                    . LodgixTranslate::translate('Price Per Week')
                    . ':</strong> ' . LodgixTranslate::translate('from') . ' '
                    . $this->ratesWeekly[0]
                    . '</li>';
            }
            if ($this->displayHighRate) {
                $html .=
                    '<li id="lodgix_weekly_rates" class="ldgxGridRate ldgxGridRateWeekly ldgxGridRateHigh ldgxGridRateWeeklyHigh"><strong>'
                    . LodgixTranslate::translate('Weekly Rate')
                    . ':</strong> '
                    . $this->ratesWeekly[0]
                    . ' - '
                    . $this->ratesWeekly[1]
                    . '</li>';
            }
        }
        if ($this->ratesMonthly) {
            if ($this->displayLowRate) {
                $html .=
                    '<li id="lodgix_monthly_rates" class="ldgxGridRate ldgxGridRateMonthly ldgxGridRateLow ldgxGridRateMonthlyLow"><strong>'
                    . LodgixTranslate::translate('Price Per Month')
                    . ':</strong> ' . LodgixTranslate::translate('from') . ' '
                    . $this->ratesMonthly[0]
                    . '</li>';
            }
            if ($this->displayHighRate) {
                $html .=
                    '<li id="lodgix_monthly_rates" class="ldgxGridRate ldgxGridRateMonthly ldgxGridRateHigh ldgxGridRateMonthlyHigh"><strong>'
                    . LodgixTranslate::translate('Monthly Rate')
                    . ':</strong> '
                    . $this->ratesMonthly[0]
                    . ' - '
                    . $this->ratesMonthly[1]
                    . '</li>';
            }
        }
        if ($this->displayBedrooms) {
            $html .=
                '<li id="lodgix_bedrooms" class="ldgxGridBeds"><strong>'
                . LodgixTranslate::translate('Bedrooms')
                . ':</strong> '
                . ($this->property->bedrooms == 0 ? LodgixTranslate::translate('Studio') : $this->property->bedrooms)
                . '</li>';
        }
        if ($this->property->category_id && $this->showCategory) {
            $html .=
                '<li id="lodgix_location" class="ldgxGridArea"><strong>'
                . LodgixTranslate::translate('Location')
                . ':</strong> '
                . $this->property->category_title_long
                . '</li>';
        }
        if ($this->displayMinStay) {
            $html .=
                '<li class="ldgxGridMinStay">'
                . $this->minStay[0]
                . ' '
                . LodgixTranslate::translate('nt min')
                . '</li>';
        }

        $html .= '</ul></div>';
        return $html;
    }

    function gridRow(
        $displayBedrooms=true,
        $displayBathrooms=true,
        $displayGuests=true,
        $displayType=true,
        $displayPets=true,
        $displayAreas=true,
        $displayRatesDailyFrom=true
    ){
        $html = '<div class="ldxGridProperty">';

        $startingRate = '';

        if($displayRatesDailyFrom){
            if($this->ratesDailyFrom[0]){
                $startingRate = '<div class="ldxStRate ldgxListingFeatCellDailyHigh">'.LodgixTranslate::translate('from').' <span>' . $this->ratesDailyFrom[0] . '</span> ' .LodgixTranslate::translate('per night'). '</div>';
            }
        }

        $html .=
            '<div class="ldgxListingFullSizeImgContainer"><div class="ldgxListingFullSizeImg" style="background-image: url('
            . $this->property->main_image_preview
            . ')"><a href="'
            . $this->permalink
            . '"><svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" viewBox="0 0 313 235" style="enable-background:new 0 0 313 235;" xml:space="preserve"></svg></a>'
            //. ($displayLearnMoreBookNow ? $this->learnMoreOrBookNow($hrefBook) : '')
            . $startingRate
            . '</div></div>'
            . '<div class="ldgxListingName"><span><a href="'
            . $this->permalink
            . '">'
            . $this->property->description
            . '</a></span>';

            if($displayType){
                $html .=
                '<span class="ldgxListingFeatCellType">'
                . LodgixTranslate::translate($this->property->proptype)
                . '</span>';
            }

            $html .=
            '</div>'
            . '<div class="ldxListingGridQuickInfo">'
            . '<div class="ldxListingGridQuickInfoColumn">';

            if($displayAreas){
                if($this->property->city){
                    $html .= '<div class="ldgxListingArea">'
                    . $this->property->city;
                    if(!empty($this->property->state)){
                        $html .= ', '
                        . $this->property->state;
                    }
                    $html .= '</div>';
                }
            }

            $html .= '</div>'
            . '<div class="ldxListingGridQuickInfoColumn">';


            $html .= '<div class="ldxListingGridQuickAmGrid">';

            if ($displayBedrooms) {
                $html .= '<div class="ldgxListingFeatCellBeds ldxListingGridQuickAmGridColumn bedrooms">'
                . '<div class="topRow">'
                . '<span>Bedrooms</span>'
                . '</div>'
                . '<div class="bottomRow">'
                . $this->property->bedrooms
                . '</div>'
                . '</div>';
            }

            if ($displayBathrooms) {
                $html .= '<div class="ldgxListingFeatCellBaths ldxListingGridQuickAmGridColumn bathrooms">'
                . '<div class="topRow">'
                . '<span>Bathrooms</span>'
                . '</div>'
                . '<div class="bottomRow">'
                . $this->property->bathrooms
                . '</div>'
                . '</div>';
            }

            if ($displayGuests) {
                $html .= '<div class="ldgxListingFeatCellGuests ldxListingGridQuickAmGridColumn noGuests">'
                . '<div class="topRow">'
                . '<span>Number of Guests</span>'
                . '</div>'
                . '<div class="bottomRow">'
                . $this->property->sleeps
                . '</div>'
                . '</div>';
            }

            if ($displayPets) {
                $petsYesNo = $this->property->pets == 1 ? 'petsYes' : 'petsNo';
                $html .= '<div class="ldgxListingFeatCellPets ldxListingGridQuickAmGridColumn petsAllowed '
                . $petsYesNo
                . ' ">'
                . '<div class="topRow">'
                . '<span>Pets Allowed</span>'
                . '</div>'
                . '</div>';
            }

            $html .= '</div>';

        $html .= '</div>';
        $html .= '</div>';
        $html .= '</div>';

        return $html;
    }

    function row(
        $smallThumbnails=true,
        $fullSizeThumbnails=true,
        $displayLearnMoreBookNow=true,
        $warning='',
        $iconSet=LodgixConst::ICON_SET_OLD,
        $displayTableIcons=true,
        $displayBathrooms=true,
        $displayGuests=true,
        $displayType=true,
        $displayPets=true,
        $displayAreas=true,
        $displayAvailability=true,
        $displayIcons=true,
        $mailUrl='',
        $differentiate=false,
        $hrefBook='',
        $gridLayout=false,
        $displayRatesDailyFrom=true
    ) {
        $pluginUrl = plugin_dir_url(plugin_basename(PLUGIN_PATH));

        // if($gridLayout){
        //     $html = $this->gridRow(
        //         $this->displayBedrooms,
        //         $displayBathrooms,
        //         $displayGuests,
        //         $displayType,
        //         $displayPets,
        //         $displayAreas,
        //         $this->displayRatesDailyFrom
        //     );
        //     return $html;
        // }

        if (empty($mailUrl)) {
            $mailUrl = 'javascript:void(0)';
        }

        $html = '<div class="ldgxShadow">';

        if ($this->property->category_id && $this->showCategory) {
            $categoryTitle = '<div class="ldgxListingArea">' . $this->property->category_title_long . '</div>';
        } else {
            $categoryTitle = '';
        }

        if ($this->displayMinStay) {
            $minStay = '<div class="ldgxListingMinStay">' . $this->minStay[0] . ' ' . LodgixTranslate::translate('nt min') . '</div>';
        } else {
            $minStay = '';
        }

        $details = preg_replace('{(<br(\s*/)?>|&nbsp;)+$}i', '', html_entity_decode(nl2br($this->property->details)));
        $details = preg_replace('#<iframe[^>]+>.*?</iframe>#is', '', $details);

        if ($smallThumbnails) {
            $html .=
                '<div class="ldgxListingImg"><a href="'
                . $this->permalink
                . '"><img border="0" src="'
                . $this->property->main_image_thumb
                . '" alt="'.$this->property->description.'"></a>'
                . ($displayLearnMoreBookNow ? $this->learnMoreOrBookNow($hrefBook) : '')
                . '</div><div class="ldgxListingName"><a href="'
                . $this->permalink
                . '">'
                . $this->property->description
                . '</a></div><div class="ldgxListingBody">'
                . $categoryTitle
                . $minStay
                . $warning
                . '<div class="ldgxListingDesc">'
                . $details
                . '</div><div class="ldgxListingSeparator"></div></div>';
        }

        if ($fullSizeThumbnails) {
            $html .=
                '<div class="ldgxListingFullSizeImg"><a href="'
                . $this->permalink
                . '"><img border="0" src="'
                . $this->property->main_image_preview
                . '" alt="'.$this->property->description.'"></a>'
                . ($displayLearnMoreBookNow ? $this->learnMoreOrBookNow($hrefBook) : '')
                . '</div><div class="ldgxListingFullSizeName"><a href="'
                . $this->permalink
                . '">'
                . $this->property->description
                . '</a></div><div class="ldgxListingFullSizeBody">'
                . $categoryTitle
                . $minStay
                . $warning
                . '<div class="ldgxListingDesc">'
                . $details
                . '</div><div class="ldgxListingSeparator"></div></div>';
        }

        if ($displayTableIcons && $iconSet != LodgixConst::ICON_SET_OLD) {
            $html .=
                '<table class="ldgxListingFeats ldgxListingFeatsIcons ldgxListingFeatsIcons'
                . LodgixConst::$ICON_SET_CLASS[$iconSet] . '">';
        } else {
            $html .= '<table class="ldgxListingFeats">';
        }

        if ($this->displayBedrooms) {
            $html .=
                '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellBeds"><th width="70" altwidth="40" alt="Beds" class="ldgxListingFeatCell ldgxListingFeatCellHeader">'
                . LodgixTranslate::translate('Bedrooms')
                . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent">'
                . ($this->property->bedrooms == 0 ? LodgixTranslate::translate('Studio') : $this->property->bedrooms)
                . '</td></tr>';
        }

        if ($displayBathrooms) {
            $html .=
                '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellBaths"><th width="70" altwidth="40" alt="Baths" class="ldgxListingFeatCell ldgxListingFeatCellHeader">'
                . LodgixTranslate::translate('Bathrooms')
                . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent">'
                . $this->property->bathrooms
                . '</td></tr>';
        }

        if ($displayGuests) {
            $html .=
                '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellGuests"><th width="70" altwidth="40" alt="Number of Guests" class="ldgxListingFeatCell ldgxListingFeatCellHeader">'
                . LodgixTranslate::translate('# of Guests')
                . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent">'
                . $this->property->sleeps
                . '</td></tr>';
        }

        if ($displayType) {
            $html .=
                '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellType"><th width="80" altwidth="40" alt="Type" class="ldgxListingFeatCell ldgxListingFeatCellHeader">'
                . LodgixTranslate::translate('Rental Type')
                . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent">'
                . LodgixTranslate::translate($this->property->proptype)
                . '</td></tr>';
        }

        if ($displayPets) {
            $petsYesNo = $this->property->pets == 1 ? 'Yes' : 'No';
            $html .=
                '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellPets ldgxListingFeatCellPets'
                . $petsYesNo
                . '"><th width="80" altwidth="40" alt="Pets" class="ldgxListingFeatCell ldgxListingFeatCellHeader">'
                . LodgixTranslate::translate('Pet Friendly')
                . '?</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent"><div class="ldgxPets'
                . $petsYesNo
                . ' ldgxPets'
                . $petsYesNo
                . LodgixConst::$ICON_SET_CLASS[$iconSet]
                . ' ldgxPets'
                . LodgixTranslate::translate($petsYesNo)
                . '"></div></td></tr>';
        }

        if ($this->ratesDaily) {
            if ($this->displayLowRate) {
                $html .=
                    '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellDaily ldgxListingFeatCellLow ldgxListingFeatCellDailyLow"><th width="100" altwidth="100" alt="Daily" class="ldgxListingFeatCell ldgxListingFeatCellHeader ldgxListingFeatDaily">'
                    . LodgixTranslate::translate('Price Per Night')
                    . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent ldgxListingFeatDaily">'
                    . LodgixTranslate::translate('from') . ' '
                    . $this->ratesDaily[0]
                    . '</td></tr>';
            }
            if ($this->displayHighRate) {
                $html .=
                    '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellDaily ldgxListingFeatCellHigh ldgxListingFeatCellDailyHigh"><th width="100" altwidth="100" alt="Daily" class="ldgxListingFeatCell ldgxListingFeatCellHeader ldgxListingFeatDaily">'
                    . LodgixTranslate::translate('Daily Rate')
                    . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent ldgxListingFeatDaily">'
                    . $this->ratesDaily[0]
                    . ' - '
                    . $this->ratesDaily[1]
                    . '</td></tr>';
            }
        }

        if ($this->ratesWeekly) {
            if ($this->displayLowRate) {
                $html .=
                    '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellWeekly ldgxListingFeatCellLow ldgxListingFeatCellWeeklyLow"><th width="100" altwidth="100" alt="Weekly" class="ldgxListingFeatCell ldgxListingFeatCellHeader ldgxListingFeatWeekly">'
                    . LodgixTranslate::translate('Price Per Week')
                    . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent ldgxListingFeatWeekly">'
                    . LodgixTranslate::translate('from') . ' '
                    . $this->ratesWeekly[0]
                    . '</td></tr>';
            }
            if ($this->displayHighRate) {
                $html .=
                    '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellWeekly ldgxListingFeatCellHigh ldgxListingFeatCellWeeklyHigh"><th width="100" altwidth="100" alt="Weekly" class="ldgxListingFeatCell ldgxListingFeatCellHeader ldgxListingFeatWeekly">'
                    . LodgixTranslate::translate('Weekly Rate')
                    . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent ldgxListingFeatWeekly">'
                    . $this->ratesWeekly[0]
                    . ' - '
                    . $this->ratesWeekly[1]
                    . '</td></tr>';
            }
        }

        if ($this->ratesMonthly) {
            if ($this->displayLowRate) {
                $html .=
                    '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellMonthly ldgxListingFeatCellLow ldgxListingFeatCellMonthlyLow"><th width="100" altwidth="100" alt="Monthly" class="ldgxListingFeatCell ldgxListingFeatCellHeader ldgxListingFeatMonthly">'
                    . LodgixTranslate::translate('Price Per Month')
                    . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent ldgxListingFeatMonthly">'
                    . LodgixTranslate::translate('from') . ' '
                    . $this->ratesMonthly[0]
                    . '</td></tr>';
            }
            if ($this->displayHighRate) {
                $html .=
                    '<tr class="ldgxListingFeatCellWrap ldgxListingFeatCellMonthly ldgxListingFeatCellHigh ldgxListingFeatCellMonthlyHigh"><th width="100" altwidth="100" alt="Monthly" class="ldgxListingFeatCell ldgxListingFeatCellHeader ldgxListingFeatMonthly">'
                    . LodgixTranslate::translate('Monthly Rate')
                    . '</th><td class="ldgxListingFeatCell ldgxListingFeatCellContent ldgxListingFeatMonthly">'
                    . $this->ratesMonthly[0]
                    . ' - '
                    . $this->ratesMonthly[1]
                    . '</td></tr>';
            }
        }

        $html .= '</table>';

        if ($displayAvailability || $displayIcons) {
            $html .= '<div class="ldgxListingButs">';
            if ($displayAvailability) {
                $html .= '<div class="ldgxListingButsBlock1">';
                if ($differentiate && $this->property->really_available && $this->property->allow_booking) {
                    $html .=
                        '<a title="'
                        . LodgixTranslate::translate('Book Now')
                        . '" href="'
                        . $this->property->booklink
                        . '"><img src="'
                        . $pluginUrl
                        . '/images/booknow.png" alt="Book Now"></a>';
                } else {
                    $html .=
                        '<a title="'
                        . LodgixTranslate::translate('Check Availability')
                        . '" href="'
                        . $this->hrefBook
                        . '" class="lodgix_check_availability_icon"></a>';
                }
                $html .= '</div>';
            }
            if ($displayIcons) {
                $html .=
                    '<div class="ldgxListingButsBlock2"><a title="Display Google Map" href="'
                    . $this->hrefMap . '" class="ldgxButton ldgxButtonMap ldgxButtonMap'
                    . LodgixConst::$ICON_SET_CLASS[$iconSet] . '"></a><a title="Contact Us" href="'
                    . $mailUrl . '" class="ldgxButton ldgxButtonMail ldgxButtonMail'
                    . LodgixConst::$ICON_SET_CLASS[$iconSet] . '"></a></div>';
            }
            $html .= '</div>';
        }

        $html .= '</div>';
        return $html;
    }

    protected function learnMoreOrBookNow($hrefBook='') {
        $labelLearnMore = LodgixTranslate::translate('Learn More');
        $labelBookNow = LodgixTranslate::translate('Book Now');
        return
            '<div class="ldgxSlashBtnWrap"><div class="ldgxSlashBtn"><a title="'
            . $labelLearnMore
            . '" href="'
            . $this->permalink
            . '" class="ldgxSlashBtnL">'
            . $labelLearnMore
            . '</a><a title="'
            . $labelBookNow
            . '" href="'
            . ($hrefBook ? $hrefBook : $this->hrefBook)
            . '" class="ldgxSlashBtnR">'
            . $labelBookNow
            . '</a></div></div>';
    }

}
