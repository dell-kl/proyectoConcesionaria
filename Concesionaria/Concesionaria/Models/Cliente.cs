using System;
using System.Collections.Generic;
using System.Text;

namespace Concesionaria.Models
{
    public class Cliente
    {
        public int cliente_id { set; get; } = 0;
        public string cliente_usuario { set; get; } = "";
        public string cliente_correo { set; get; } = "";
        public string cliente_celular { set; get; } = "";

        public string cliente_genero { set; get; } = "";

        public string cliente_provincia { set; get; } = "";

        public string cliente_archivoRuta { set; get; } = "wwwroot/img/pictures/perfil.png";

        public string cliente_contrasena { set; get; } = "ninguna";

        public string cliente_rol { set; get; } = "2";

        public string cliente_cedula { set; get; } = "";
    }
}
