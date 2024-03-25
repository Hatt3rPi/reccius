<?php
//archivo: pages\cotizador\busqueda_cotizacion.php
//Elaborado por: @ratapan

//Todo:
// dentificador
// fecha creación
// nombre cliente 
// valor cotización ($)

session_start();
require_once "/home/customw2/conexiones/config_reccius.php";
// Verificar si la variable de sesión "usuario" no está establecida o está vacía.
if (!isset($_SESSION['usuario']) || empty($_SESSION['usuario'])) {
    // Redirigir al usuario a la página de inicio de sesión.
    header("Location: login.html");
    exit;
}
?>
<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <title>Ingreso de cotización</title>
    <link rel="stylesheet" href="../assets/css/calidad.css">
    <link rel="stylesheet" href="../assets/css/cotizador.css">
</head>

<body>
    <div class="form-container">
        <h1>Busqueda de cotizaciones </h1>

        <br>
        <br>
        <h2 class="section-title">Buscador:</h2>
        <div class="container">
            <div class="form-row justify-content-start">
                <label> Busqueda por Nombre
                    <input type="radio" name="buscador_tipo" id="nombre" value="nombre" checked>
                </label>
                <label class="pl-3"> Cotización
                    <input type="radio" name="buscador_tipo" id="contizacion" value="contizacion">
                </label>
            </div>
            <div class="form-row">
                <div class="col w-100">
                    <input type="text" name="buscador" id="buscador" class="form-control" placeholder="Ingresa el id de cotización...">
                </div>
                <button class="buscador_btn">Buscar</button>
            </div>
        </div>
        <br>
        <div class="alert alert-warning text-center" role="alert">
            Acá se podrían ver las últimas cotizaciones.
        </div>
        <div id="contenedor_tabla" class="container">
            <table id="resultadosTabla" class="table table-striped table-bordered" width="100%"></table>
            <div class="form-row justify-content-end" id="resultadosTabla_paginacion"></div>
        </div>
    </div>
</body>

</html>
<script>
    var resultadosTabla = 0;
    var cotizacionesLista = [];
    var maxExtraction = 0
    var pageTabla = 0

    var resultadosTablaPaginacion = $('#resultadosTabla_paginacion')

    /*
    Inicio
*/
    cargaTablaResult({
        id: null,
        action: null
    });

    function cargaTablaResult({
        id = null,
        accion = null
    }) {

        resultadosTabla = new DataTable('#resultadosTabla', {
            "paging": false,
            "info": false,
            "searching": false,
            "lengthChange": false,
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.7/i18n/es-ES.json'
            },
            columns: [{
                    title: 'Cotización'
                },
                {
                    title: 'Fecha Creacion'
                },
                {
                    title: 'Cliente'
                },
                {
                    title: 'Valor($)'
                },
                {
                    title: 'Actividad'
                }
            ]
        });

    }



    function generarArrayRango(inicio, fin, paso = 1) {
        let arrayRango = [];
        for (let i = inicio; i <= fin; i += paso) {
            arrayRango.push(i);
        }
        return arrayRango;
    }

    function setBtns(num) {
        resultadosTablaPaginacion.empty();
        var rango = generarArrayRango(0, num - 1)
        for (var i = 0; i < rango.length; i++) {
            resultadosTablaPaginacion.append(`<button class="btn ${pageTabla == i ? 'btn-primary' : '' }  justify-content-center p-0" style="width: 24px;" onclick="fillData(${rango[i]})">${rango[i] + 1}</button>`)
        }

    }

    function fillData(page = 0) {
        pageTabla = page
        cleanTableResume()
        maxExtraction = fakeData.length
        setBtns(Math.trunc(maxExtraction / 10))
        var extraction = generarArrayRango(page * 10, page * 10 + 10).map(i => fakeData[i]);
        cotizacionesLista = []
        extraction.forEach(element => {
            setToList(element)
        })
    }
    /*
    Manejar contizaciones
*/
    function setToList(listObject) {
        cotizacionesLista.push(listObject)
        console.log(listObject);
        addRowTable({
            cotizacion: listObject.cotizacion,
            fecha_creacion: listObject.fecha_creacion,
            cliente: listObject.cliente,
            valor: listObject.precio
        })
    }

    function addRowTable({
        cotizacion,
        fecha_creacion,
        cliente,
        valor
    }) {
        var filaNueva = [
            `<p>${cotizacion}</p>`,
            `<p>${fecha_creacion}</p>`,
            `<p>${cliente}</p>`,
            `<p>$${valor}</p>`,
            `<button type="button" data-cotizacion="${cotizacion}" class="btn-ver">Ver</button>`
        ];
        resultadosTabla.row.add(filaNueva);
        resultadosTabla.draw();
    }

    function cleanTableResume() {
        var resultadosTabla = $('#resultadosTabla').DataTable();
        resultadosTabla.clear().draw();
    }
    /*
    Fake Data
*/

    var fakeData = [{
            cotizacion: "KRU64QRK7DR",
            cliente: "Hadley Patterson",
            fecha_creacion: "21/13/2023",
            rut: "471722-8",
            precio: 18482
        },
        {
            cotizacion: "KUT63KSZ1OF",
            cliente: "Kennan Burgess",
            fecha_creacion: "11/41/2022",
            rut: "46576394-9",
            precio: 8221
        },
        {
            cotizacion: "KKP59RBP1NI",
            cliente: "Keely Wolfe",
            fecha_creacion: "15/20/2024",
            rut: "40381677-9",
            precio: 14863
        },
        {
            cotizacion: "LVP37TDV3RW",
            cliente: "Yen Foreman",
            fecha_creacion: "01/20/2022",
            rut: "161287-5",
            precio: 16241
        },
        {
            cotizacion: "FWV69ZXT4DH",
            cliente: "Connor May",
            fecha_creacion: "20/34/2023",
            rut: "1232807-9",
            precio: 9514
        },
        {
            cotizacion: "MXR31VBB8RI",
            cliente: "Dillon Peterson",
            fecha_creacion: "18/05/2023",
            rut: "7655534-6",
            precio: 10801
        },
        {
            cotizacion: "YYI12OGA4CG",
            cliente: "Hunter Christian",
            fecha_creacion: "09/44/2024",
            rut: "40692441-6",
            precio: 14086
        },
        {
            cotizacion: "BWA87EKC8SE",
            cliente: "Camden Frye",
            fecha_creacion: "15/35/2022",
            rut: "49998376-K",
            precio: 17631
        },
        {
            cotizacion: "RCU52QUD5BQ",
            cliente: "Cassady Hutchinson",
            fecha_creacion: "13/16/2023",
            rut: "1076639-7",
            precio: 7262
        },
        {
            cotizacion: "LUQ58GGS4HD",
            cliente: "Uta Merrill",
            fecha_creacion: "11/43/2022",
            rut: "4438868-5",
            precio: 8729
        },
        {
            cotizacion: "BSD93FFU2MM",
            cliente: "Kalia Pruitt",
            fecha_creacion: "26/56/2022",
            rut: "45663714-0",
            precio: 11295
        },
        {
            cotizacion: "UFV86GNW4ET",
            cliente: "Ulric Landry",
            fecha_creacion: "24/32/2024",
            rut: "15977415-5",
            precio: 16283
        },
        {
            cotizacion: "SIS17EJV3XF",
            cliente: "Genevieve Kidd",
            fecha_creacion: "09/24/2024",
            rut: "5950396-0",
            precio: 8489
        },
        {
            cotizacion: "SBU17LWH2SO",
            cliente: "Victor Hardy",
            fecha_creacion: "23/12/2023",
            rut: "698660-9",
            precio: 18803
        },
        {
            cotizacion: "BPG78LHF4JG",
            cliente: "Aristotle Clements",
            fecha_creacion: "27/36/2024",
            rut: "1890659-7",
            precio: 10842
        },
        {
            cotizacion: "PFU10YHS4LW",
            cliente: "Tatiana Dunn",
            fecha_creacion: "11/34/2025",
            rut: "7347772-7",
            precio: 7988
        },
        {
            cotizacion: "DVL29YDR8HF",
            cliente: "Bryar Heath",
            fecha_creacion: "09/24/2025",
            rut: "46681940-9",
            precio: 10248
        },
        {
            cotizacion: "PHB45KNF0CY",
            cliente: "Angelica Leblanc",
            fecha_creacion: "18/19/2024",
            rut: "33825362-1",
            precio: 16818
        },
        {
            cotizacion: "HXX35XGZ4QT",
            cliente: "Rhonda Colon",
            fecha_creacion: "16/43/2025",
            rut: "2974968-K",
            precio: 9420
        },
        {
            cotizacion: "IFR10FGU0GH",
            cliente: "Lester Everett",
            fecha_creacion: "01/44/2023",
            rut: "21266210-0",
            precio: 12859
        },
        {
            cotizacion: "SFP94UHP3SK",
            cliente: "Rhoda Pierce",
            fecha_creacion: "14/33/2022",
            rut: "50506607-3",
            precio: 18255
        },
        {
            cotizacion: "CWM27HPJ1CL",
            cliente: "Quintessa Ryan",
            fecha_creacion: "24/35/2024",
            rut: "44475667-5",
            precio: 13353
        },
        {
            cotizacion: "VIT56QET8NF",
            cliente: "Jameson Caldwell",
            fecha_creacion: "13/27/2023",
            rut: "12767803-0",
            precio: 19168
        },
        {
            cotizacion: "OIC17NXX4QH",
            cliente: "Travis Weber",
            fecha_creacion: "11/21/2024",
            rut: "12768443-K",
            precio: 13418
        },
        {
            cotizacion: "QAS10JZV8KT",
            cliente: "Nevada Hobbs",
            fecha_creacion: "10/44/2022",
            rut: "40440243-9",
            precio: 13159
        },
        {
            cotizacion: "DFK53RDA8EL",
            cliente: "Reese Contreras",
            fecha_creacion: "27/33/2024",
            rut: "22575724-0",
            precio: 10576
        },
        {
            cotizacion: "UYP16IPJ9KR",
            cliente: "Jermaine Kemp",
            fecha_creacion: "28/34/2023",
            rut: "42953327-9",
            precio: 5382
        },
        {
            cotizacion: "ZPU35XMO4VJ",
            cliente: "Russell Hunter",
            fecha_creacion: "14/17/2023",
            rut: "98307-1",
            precio: 6316
        },
        {
            cotizacion: "SQM33FZF9QB",
            cliente: "Amery Jensen",
            fecha_creacion: "23/50/2023",
            rut: "42858688-3",
            precio: 11209
        },
        {
            cotizacion: "TNG91WXS2VK",
            cliente: "Scarlet Day",
            fecha_creacion: "16/57/2023",
            rut: "1081727-7",
            precio: 19927
        },
        {
            cotizacion: "HQR85FTO3RG",
            cliente: "Philip Miranda",
            fecha_creacion: "06/30/2024",
            rut: "26492140-6",
            precio: 11040
        },
        {
            cotizacion: "FGM22VBD8PC",
            cliente: "Jasper Clemons",
            fecha_creacion: "12/29/2023",
            rut: "8794257-0",
            precio: 5518
        },
        {
            cotizacion: "GTI92BSB3AD",
            cliente: "Candice Branch",
            fecha_creacion: "14/43/2024",
            rut: "647469-1",
            precio: 13354
        },
        {
            cotizacion: "VHS78RMS8AH",
            cliente: "Robert Todd",
            fecha_creacion: "06/37/2023",
            rut: "39240532-1",
            precio: 14957
        },
        {
            cotizacion: "LZT24RYK2OM",
            cliente: "Ingrid Stokes",
            fecha_creacion: "15/14/2024",
            rut: "31462385-1",
            precio: 19003
        },
        {
            cotizacion: "BEY16PYE4BT",
            cliente: "Jin Puckett",
            fecha_creacion: "01/40/2023",
            rut: "42297409-1",
            precio: 15803
        },
        {
            cotizacion: "PYC14FPH3MN",
            cliente: "Dylan Yang",
            fecha_creacion: "01/04/2024",
            rut: "13378525-6",
            precio: 6793
        },
        {
            cotizacion: "UZW07DWK7WH",
            cliente: "Tyler Wiley",
            fecha_creacion: "17/02/2024",
            rut: "22351235-6",
            precio: 9211
        },
        {
            cotizacion: "COH89TPK6UT",
            cliente: "Ronan Nielsen",
            fecha_creacion: "21/55/2023",
            rut: "2412115-1",
            precio: 11286
        },
        {
            cotizacion: "VCR77BFE6QV",
            cliente: "Aiko Parsons",
            fecha_creacion: "19/03/2024",
            rut: "16773995-4",
            precio: 16910
        },
        {
            cotizacion: "BHK06SWN6TN",
            cliente: "Zia Skinner",
            fecha_creacion: "19/45/2024",
            rut: "2255969-9",
            precio: 18835
        },
        {
            cotizacion: "LNL24ICU6XU",
            cliente: "Wendy Drake",
            fecha_creacion: "12/49/2024",
            rut: "23370123-8",
            precio: 18714
        },
        {
            cotizacion: "NNX69GEH4JJ",
            cliente: "Knox Welch",
            fecha_creacion: "23/08/2023",
            rut: "49709107-1",
            precio: 5303
        },
        {
            cotizacion: "JQK69OLG8XJ",
            cliente: "Hilary Mcclure",
            fecha_creacion: "17/06/2024",
            rut: "15717701-K",
            precio: 8284
        },
        {
            cotizacion: "SDB74LXM8KR",
            cliente: "Bradley Jordan",
            fecha_creacion: "18/44/2023",
            rut: "11882520-9",
            precio: 13858
        },
        {
            cotizacion: "UBS82BSW0JP",
            cliente: "Tiger Levy",
            fecha_creacion: "18/17/2024",
            rut: "40496178-0",
            precio: 5945
        },
        {
            cotizacion: "GWX20MJI2KD",
            cliente: "Shana Castillo",
            fecha_creacion: "30/31/2024",
            rut: "16402140-8",
            precio: 13479
        },
        {
            cotizacion: "CSE12GJP2PG",
            cliente: "Conan Mann",
            fecha_creacion: "18/55/2023",
            rut: "11410294-6",
            precio: 18840
        },
        {
            cotizacion: "RID03MWX1JM",
            cliente: "Stacy Tyler",
            fecha_creacion: "13/16/2023",
            rut: "39700760-K",
            precio: 11719
        },
        {
            cotizacion: "QVA03EYZ1IX",
            cliente: "Kadeem Hebert",
            fecha_creacion: "02/38/2023",
            rut: "49237867-4",
            precio: 19698
        },
        {
            cotizacion: "QVD43TFW7YM",
            cliente: "Denise Wallace",
            fecha_creacion: "23/44/2025",
            rut: "9723779-4",
            precio: 15361
        },
        {
            cotizacion: "SYA72WVW6KD",
            cliente: "Omar Weaver",
            fecha_creacion: "18/13/2022",
            rut: "614469-1",
            precio: 19198
        },
        {
            cotizacion: "VRN40PFY5JO",
            cliente: "Rhea Wilkerson",
            fecha_creacion: "22/25/2023",
            rut: "3345680-8",
            precio: 13243
        },
        {
            cotizacion: "NGQ80IJP6KG",
            cliente: "Noelani Mullins",
            fecha_creacion: "15/03/2023",
            rut: "27107332-1",
            precio: 10053
        },
        {
            cotizacion: "QNQ33JPP3NE",
            cliente: "Hamish Contreras",
            fecha_creacion: "27/49/2024",
            rut: "25753366-2",
            precio: 15200
        },
        {
            cotizacion: "BVH21IRQ6TS",
            cliente: "Odessa Levy",
            fecha_creacion: "20/03/2024",
            rut: "31199578-2",
            precio: 8006
        },
        {
            cotizacion: "VQD71VNO1PF",
            cliente: "Emma Contreras",
            fecha_creacion: "06/43/2023",
            rut: "29641620-7",
            precio: 19079
        },
        {
            cotizacion: "IUY55XRE8FR",
            cliente: "Katell Burnett",
            fecha_creacion: "15/35/2024",
            rut: "41502302-2",
            precio: 15868
        },
        {
            cotizacion: "ELC87YAN0VV",
            cliente: "Jaquelyn Steele",
            fecha_creacion: "13/32/2023",
            rut: "1352220-0",
            precio: 9623
        },
        {
            cotizacion: "RJX68QGX4DA",
            cliente: "Hammett Huber",
            fecha_creacion: "27/05/2023",
            rut: "18148549-3",
            precio: 18932
        },
        {
            cotizacion: "DHW39QYF5GK",
            cliente: "Ramona Harris",
            fecha_creacion: "14/22/2023",
            rut: "23533936-6",
            precio: 12908
        },
        {
            cotizacion: "BBD43MYQ4DM",
            cliente: "Nissim Walker",
            fecha_creacion: "08/50/2025",
            rut: "22939840-7",
            precio: 7870
        },
        {
            cotizacion: "DLO64QSR2RV",
            cliente: "Moana Johnston",
            fecha_creacion: "10/57/2024",
            rut: "44952584-1",
            precio: 14845
        },
        {
            cotizacion: "GIB85CEO8MK",
            cliente: "Rogan Mckenzie",
            fecha_creacion: "19/25/2024",
            rut: "28886173-0",
            precio: 17442
        },
        {
            cotizacion: "JKP17LPK6KK",
            cliente: "Salvador Ford",
            fecha_creacion: "19/25/2025",
            rut: "24341506-3",
            precio: 18367
        },
        {
            cotizacion: "SGR33MDI8BR",
            cliente: "Karly Adams",
            fecha_creacion: "22/19/2022",
            rut: "21806855-3",
            precio: 5612
        },
        {
            cotizacion: "TLQ88GJM3ND",
            cliente: "Grace Little",
            fecha_creacion: "25/34/2024",
            rut: "30952795-K",
            precio: 7666
        },
        {
            cotizacion: "RYI45ROU1VJ",
            cliente: "Jessamine Leonard",
            fecha_creacion: "12/46/2024",
            rut: "6289974-3",
            precio: 19896
        },
        {
            cotizacion: "NCX52PGH3WU",
            cliente: "Carlos Pugh",
            fecha_creacion: "02/10/2024",
            rut: "41395441-K",
            precio: 13310
        },
        {
            cotizacion: "DRP21HNT3LK",
            cliente: "Montana Vinson",
            fecha_creacion: "28/24/2023",
            rut: "35506241-4",
            precio: 10347
        },
        {
            cotizacion: "ESM10MFF3UP",
            cliente: "Eliana Montgomery",
            fecha_creacion: "18/50/2024",
            rut: "40635515-2",
            precio: 18098
        },
        {
            cotizacion: "QZW44IJS6IX",
            cliente: "Ezra Evans",
            fecha_creacion: "01/04/2023",
            rut: "929141-5",
            precio: 9721
        },
        {
            cotizacion: "THB22RKQ5CQ",
            cliente: "Fuller Hayes",
            fecha_creacion: "05/35/2024",
            rut: "37761901-3",
            precio: 13063
        },
        {
            cotizacion: "EFL94WGO8EG",
            cliente: "Madaline Christensen",
            fecha_creacion: "16/13/2023",
            rut: "37498244-3",
            precio: 12600
        },
        {
            cotizacion: "SIT88GWF2FN",
            cliente: "Charles Hurst",
            fecha_creacion: "06/35/2024",
            rut: "3763819-6",
            precio: 19195
        },
        {
            cotizacion: "WFK30DRK3BL",
            cliente: "Kelsey Nelson",
            fecha_creacion: "12/34/2022",
            rut: "27798316-8",
            precio: 7166
        },
        {
            cotizacion: "QUB78BOW6FQ",
            cliente: "Cathleen Keith",
            fecha_creacion: "14/41/2022",
            rut: "35364939-6",
            precio: 9954
        },
        {
            cotizacion: "KLR62TRC4RS",
            cliente: "Declan Grant",
            fecha_creacion: "06/07/2022",
            rut: "13568905-K",
            precio: 18688
        },
        {
            cotizacion: "IPA66UFK7ZG",
            cliente: "Sage Logan",
            fecha_creacion: "29/50/2024",
            rut: "4927979-5",
            precio: 16494
        },
        {
            cotizacion: "YWK71XHO8IV",
            cliente: "Tallulah Cotton",
            fecha_creacion: "30/58/2023",
            rut: "47989248-2",
            precio: 10615
        },
        {
            cotizacion: "XSS25HEA8QN",
            cliente: "Lesley Wright",
            fecha_creacion: "04/38/2025",
            rut: "46410568-9",
            precio: 19038
        },
        {
            cotizacion: "BFA84MCC1VC",
            cliente: "Michael Delgado",
            fecha_creacion: "08/09/2023",
            rut: "21480917-6",
            precio: 8592
        },
        {
            cotizacion: "SUA44QKG6PO",
            cliente: "Emery Bryant",
            fecha_creacion: "23/07/2024",
            rut: "37919253-K",
            precio: 18013
        },
        {
            cotizacion: "YEP35TSL7UH",
            cliente: "Murphy Mcknight",
            fecha_creacion: "11/01/2024",
            rut: "256059-3",
            precio: 14414
        },
        {
            cotizacion: "FTL14IGD4IK",
            cliente: "Carl Blackburn",
            fecha_creacion: "04/21/2023",
            rut: "24849731-9",
            precio: 17323
        },
        {
            cotizacion: "OLJ32XUW6HX",
            cliente: "Kane Stanley",
            fecha_creacion: "26/58/2024",
            rut: "8758510-7",
            precio: 10860
        },
        {
            cotizacion: "JPU38DQE8IJ",
            cliente: "Rafael Haley",
            fecha_creacion: "22/11/2023",
            rut: "39514389-1",
            precio: 13529
        },
        {
            cotizacion: "NDJ44JNF0MX",
            cliente: "Anjolie Gordon",
            fecha_creacion: "24/25/2023",
            rut: "41330194-7",
            precio: 12538
        },
        {
            cotizacion: "RGS69DEC7RW",
            cliente: "Berk Price",
            fecha_creacion: "21/16/2023",
            rut: "26448834-6",
            precio: 18429
        },
        {
            cotizacion: "SUT14QMU8OY",
            cliente: "Vera Benson",
            fecha_creacion: "28/27/2023",
            rut: "8897277-5",
            precio: 8990
        },
        {
            cotizacion: "BSZ03JXS3UI",
            cliente: "Martha Williamson",
            fecha_creacion: "14/41/2023",
            rut: "34105134-7",
            precio: 16462
        },
        {
            cotizacion: "NUS15PYO1MU",
            cliente: "Clarke Romero",
            fecha_creacion: "07/40/2023",
            rut: "36535486-3",
            precio: 19983
        },
        {
            cotizacion: "CLV60UQP9MI",
            cliente: "Rachel Craft",
            fecha_creacion: "01/57/2023",
            rut: "7332342-8",
            precio: 12023
        },
        {
            cotizacion: "SHE50QOF9XC",
            cliente: "Emily Owens",
            fecha_creacion: "05/47/2023",
            rut: "16309487-8",
            precio: 13938
        },
        {
            cotizacion: "VNG37RVT4XH",
            cliente: "Kirsten Collier",
            fecha_creacion: "21/31/2022",
            rut: "39170836-3",
            precio: 13585
        },
        {
            cotizacion: "KPJ13CDV5WM",
            cliente: "Lamar Frost",
            fecha_creacion: "26/43/2023",
            rut: "10130558-9",
            precio: 6492
        },
        {
            cotizacion: "XSZ42IWT2QN",
            cliente: "Jerome Bailey",
            fecha_creacion: "27/09/2023",
            rut: "21231137-5",
            precio: 10375
        },
        {
            cotizacion: "UXT56BHX4TE",
            cliente: "Stacey Mercer",
            fecha_creacion: "01/01/2023",
            rut: "3696266-6",
            precio: 7168
        },
        {
            cotizacion: "DWN00GRO7VH",
            cliente: "Mallory Kirk",
            fecha_creacion: "29/29/2024",
            rut: "42709938-5",
            precio: 5111
        },
        {
            cotizacion: "BOM68PIS3FL",
            cliente: "Warren Pollard",
            fecha_creacion: "07/27/2024",
            rut: "424281-5",
            precio: 10068
        }
    ]

    fillData();
</script>