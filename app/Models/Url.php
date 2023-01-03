<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Url extends Model
{
    const ALL_PROFILE = Url_Path::URL.'users/profile.jpeg';
    const URL_RAPPORT = Url_Path::URL.'reports/';
    const URL_SLIDERS = Url_Path::URL.'sliders/';
    const URL_ACTUALITES = Url_Path::URL.'actualites/';
    const URL_ENQUETES = Url_Path::URL.'enquetes/';
    const URL_PRESENTATION = Url_Path::URL.'presentations/';
    const URL_PARTENAIRES = Url_Path::URL.'partenaires/';
    const URL_GALLERIES = Url_Path::URL.'galleries/';
    const URL_TEAMS = Url_Path::URL.'teams/';
    const URL_VICTIMES = Url_Path::URL.'victimes/';
    const URL_USERS = Url_Path::URL.'users/';
}
