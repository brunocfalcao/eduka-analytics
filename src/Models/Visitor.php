<?php

namespace Eduka\Analytics\Models;

use Eduka\Abstracts\EdukaModel;

/**
 * A Visitor is any source.
 */
class Visitor extends EdukaModel
{
    public function visits()
    {
        return $this->hasMany(Visit::class);
    }

    /**
     * Adds GeoData from the retrieved visitor IP address, to the contextd
     * visitor model instance.
     *
     * @param array $data
     *
     * @return void
     */
    public function updateGeoData(array $data)
    {
        $this->continent = $data['continent'];
        $this->continentCode = $data['continentCode'];
        $this->country = $data['country'];
        $this->countryCode = $data['countryCode'];
        $this->region = $data['region'];
        $this->regionName = $data['regionName'];
        $this->city = $data['city'];
        $this->district = $data['district'];
        $this->zip = $data['zip'];
        $this->latitude = $data['lat'];
        $this->longitude = $data['lon'];
        $this->timezone = $data['timezone'];
        $this->currency = $data['currency'];

        $this->update();
    }
}
