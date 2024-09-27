using System;
using System.Collections.Generic;
using System.Text;

namespace Concesionaria.Models
{
    public class Resumen
    {
        public string cliente_id { set; get;  }
        public string cliente_usuario { set; get; }
        public string cliente_correo { set; get;  }
        public string cliente_celular { set; get;  }
        public string cliente_genero { set; get;  }
        public string cliente_provincia { set; get;  }
        public string cliente_archivoRuta { set; get;  }
        public string cliente_rol { set; get; }
        public string cliente_cedula { set; get;  }
        public string compra_id { set; get;  }
        public string compra_codigoCliente { set; get;  }
        public string compra_vehiculoCodigo { set; get;  }
        public string compra_compraDetalleId { set; get;  }
        public string compra_matricula { set; get;  }
        public string compraDetalle_id { set; get; }
        public string compraDetalle_precio { set; get;  }
        public string compraDetalle_valorTotal { set; get;  }
        public string compraDetalle_cantidad { set; get;  }
        public string compraDetalle_comprobante { set; get;  }
        public string vehiculo_marca { set; get; }
        public string vehiculo_modelo { set; get;  }
        public string vehiculo_ano { set; get;  }
        public string vehiculo_rutaImagen { set; get;  }
        public string vehiculo_puertas { set; get;  }
        public string vehiculo_color { set; get;  }
    }
}
