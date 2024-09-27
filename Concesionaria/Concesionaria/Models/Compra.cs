using System;
using System.Collections.Generic;
using System.Text;

namespace Concesionaria.Models
{
    public class Compra
    {
        public int compra_id { set; get; }
        public string compra_codigoCliente { set; get; }
        public string compra_vehiculoCodigo { set; get; }
        public string compra_compraDetalleId { set; get; }
        public string compra_matricula { set; get; }
    }
}
