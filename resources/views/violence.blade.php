<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Import</title>
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Karla:ital,wght@0,200;0,700;1,700;1,800&family=Poppins:ital,wght@0,100;0,200;0,600;1,300;1,400;1,700;1,900&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.6.0/jquery.min.js"  crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.9.2/dist/umd/popper.min.js" integrity="sha384-IQsoLXl5PILFhosVNubq5LC7Qb9DXgDA9i+tQ8Zj3iwWAwPtgFTxbJ8NT4GN1R8p" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.min.js" integrity="sha384-cVKIPhGWiC2Al4u+LWgxfKTRIcfu0JTxR+EQDz/bgldoEyl4H0zUF0QKbrJ0EcQF" crossorigin="anonymous"></script>
</head>
<body>

<style type="text/css">

    @import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.2/css/all.min.css");

    *{
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    nav{
        width: 90%;
        height: 100%;
        margin: 2px;
        background-color: white;
        border-radius: 10px;
        overflow: hidden;
    }

    header{
        height: 100%;
    }
    aside{
        width: 20%;
        height: 100%;
        background-color: #6698FF;
        float: left;
    }
    aside h1 img{
        width: 51px;
        height: 120px;
    }
    aside h3 span{
        font-size: 30px;
        line-height: 50px;
        margin-left: 6px;
        color: #6698FF;
    }
    aside ul{
        padding: 5px 0 5px 30px;

    }
    aside ul li{
        list-style: none;
        font-weight: bold;
        margin: 5px auto;
        position: relative;
        overflow: hidden;
        max-height: 35px;
        line-height: 35px;
        transition: 1s;
        text-transform: capitalize;
        color: #ffffff;
    }
    aside ul:first-child{
        max-height: 50px;
    }
    aside ul li:hover{
        /*background: #5DADE2;*/
        background: #000000;
        border-radius: 5px;
        margin-right: 10px;
    }
    aside ul:first-child li:hover{
        background: none;
    }
    aside ul li a{
        color:#e3f0fc;
        text-decoration: none;
    }
    aside ul li a i{
        width: 30px;
        padding: 7px;
        margin-right: 10px;
    }
    aside ul li a i.fa-angle-right{
        position: absolute;
        right: 0;
        top: 5px;
        transition: 0.5s;
    }

    aside ul li>ul{
        padding: 1px;
        margin: 0 0 0 20px;
    }
    aside ul li>ul li{
        height: 25px;
        line-height: 20px;
        font-size: 12px;
        padding: 1px 1px 1px 20px;
        font-weight: inherit;
        border-left: 2px solid #fff0f0;
        color: #fff0f0;
        cursor: pointer;
    }
    aside ul li>ul li a,
    aside ul li:hover>ul li a{
        background: #ffffff;
        color: #6698FF;
        border-radius: 3px;
    }
    aside ul li:hover{
        max-height: 400px;
        border-radius: 5px 0 0 5px;
        color: #6698FF;
    }
    aside ul li:hover a{
        color: #6698FF;;
        display: inherit;
        background: #ffffff;
    }
    aside ul li:hover a i.fa-angle-right{
        transform: rotate(90deg);
        top: 10px;
    }

    .log{
        margin: 30px;
    }

    .hr_bar{
        background-color: #6698FF;
        height: 2px;
        margin-bottom: 80px;
        margin-left: 160px;
    }

    .form_data{
        margin-bottom: 200px;
    }
    .table_data{
        background-color: #6698FF;
        color: #ffffff;
    }

</style>


<nav>
    <aside>
        <div >
            <img class="log" src="assets/img/obs_logo.png" style="width: 85px; height: 85px;" alt="">
        </div>
        <ul>
            <li id="home"><a routerLink="/landing"><i class="fas fa-home"></i><span>Accueil</span></a></li>

            <li id="apropos"><a routerLink="/details-presentation"><i class="fas fa-globe"></i><span>Web</span>
                    <i class="fas fa-angle-right"></i></a>
                <ul>
                    <li><a href="https://www.observatoire-td.org/#/landing"><p>Accueil</p></a></li>
                </ul>
            </li>

            <li id="chart"><a routerLink="/infractions"><i class="fas fa-line-chart"></i><span>Infractions</span>
                    <i class="fas fa-angle-right"></i></a>
                <ul>
                    <li><a href="{{url('/infractions')}}"><p>Infractions</p></a></li>
                    <li><a href="{{url('/enquetes')}}"><p>Enquetes</p></a></li>
                    <li><a href="{{url('/type-infra')}}"><p>Type d'Infraction</p></a></li>
                    <li><a href="{{url('/lieu-infras')}}"><p>Lieu Infraction</p></a></li>
                    <li><a href="{{url('/victimes')}}"><p>Victimes</p></a></li>
                    <li><a href="{{url('/etatInfras')}}"><p>Etat Infrations</p></a></li>
                    <li><a href="{{url('/crimes')}}"><p>Etat Infra Crimes</p></a></li>
                    <li><a href="{{url('/violences')}}"><p>Etat Infra Violences</p></a></li>
                    <li><a href="{{url('/deontologies')}}"><p>Etat Infra Deontologies</p></a></li>
                </ul>
            </li>

            <li id="user"><a routerLink="/type-medias"><i class="fas fa-paw"></i><span>Sources</span>
                    <i class="fas fa-angle-right"></i></a>
                <ul>
                    <li><a href="{{url('/source-judiciaires')}}"><p>Sources Judiciaires</p></a></li>
                    <li><a href="{{url('/type-medias')}}"><p>Type des Medias</p></a></li>
                    <li><a href="{{url('/source-medias')}}"><p>Sources Medias</p></a></li>
                    <li><a href="{{url('/osc-source')}}"><p>Source Org Societe Civile</p></a></li>
                    <li><a href="{{url('/fsi-source')}}"><p>Force de Securite Interieur</p></a></li>
                </ul>
            </li>

            <li id="ecom"><a routerLink="/activites"><i class="fas fa-diamond"></i><span>Divers</span>
                    <i class="fas fa-angle-right"></i></a>
                <ul>
                    <li><a href="{{url('/catpros')}}"><p>Categorie Pro</p></a></li>
                    <li><a href="{{url('/provinces')}}"><p>Provinces</p></a></li>
                </ul>
            </li>
        </ul>
    </aside>
    <header>

        <section style="padding-top: 60px">
            <div class="container">

                <div class="row">
                    <div class="col-md-12"><h5 class="text-center pt-4">Listes des Infractions</h5></div>
                    <div class="col-md-12">
                        <div class="row">
                            <div class="col-md-10"></div>
                            <div class="col-md-2">
                                <a class="btn btn-sm btn-outline-primary float-right ml-3" href="{{url('/violence-export')}}">Exporter</a>
                            </div>
                        </div>

                        <table class="table">
                            <thead>
                            <tr>
                                <th scope="col">Date</th>
                                <th scope="col">Localité</th>
                                <th scope="col">Provinces</th>
                                <th scope="col">Type d'Infos</th>
                                <th scope="col">Infraction</th>
                                <th scope="col">Consquence</th>
                                <th scope="col">Source Infos</th>
                                <th scope="col">Tranche d'age</th>
                                <th scope="col">Plainte</th>
                            </tr>
                            </thead>
                            <tbody>
                            @if(count($infractions))
                                @foreach($infractions as $infra)
                                    <tr>
                                        <td>{{ \Carbon\Carbon::parse($infra->date)->format('d/m/Y')}}</td>
                                        <td scope="row">{{$infra->localite}}</td>
                                        <td>{{$infra->province}}</td>
                                        <td>{{$infra->type_infraction}}</td>
                                        <td>{{$infra->infractions}}</td>
                                        <td>{{$infra->consquence}}</td>
                                        <td>{{$infra->source_information}}</td>
                                        <td>{{$infra->tranche_age}}</td>
                                        <td>{{$infra->plainte}}</td>
                                    </tr>
                                @endforeach
                            @else
                                <tr>
                                    <td colspan="5" class="text-center">Pas des données a exporter</td>
                                </tr>
                            @endif
                            </tbody>
                        </table>

                    </div>
                </div>
            </div>
        </section>



    </header>
</nav>


</body>
</html>

