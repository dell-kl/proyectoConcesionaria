using System;
using System.Collections.Generic;
using System.Text;

namespace Concesionaria.Models
{
    public class CompraDetalle
    {
        public int compraDetalle_id { set; get; }
        public string compraDetalle_precio { set; get; }
        public string compraDetalle_valorTotal { set; get; }
        public string compraDetalle_cantidad { set; get; }
        public string compraDetalle_comprobante { set; get; }
    }
}
