using System;
using System.Collections.Generic;
using System.Text;

namespace Concesionaria.Models
{
    public class Vehiculo
    {
        public string vehiculo_codigo { get; set; } = "";
        public string vehiculo_modelo { set; get; }
        public string vehiculo_marca { set; get; }
        public string vehiculo_color { set; get; }
        public string vehiculo_ano { set; get; } 

        public string vehiculo_precio { set; get; }
        public string vehiculo_rutaImagen { set; get; } = "wwwroot/img/picturesAutos/auto_marca.jpg";
        public string vehiculo_puertas { set; get; }
    }
}
