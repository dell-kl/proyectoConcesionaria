using Rg.Plugins.Popup.Services;
using System;
using System.Collections.Generic;
using System.Linq;
using System.Text;
using System.Threading.Tasks;

using Xamarin.Forms;
using Xamarin.Forms.Xaml;

namespace Concesionaria.Views.Alertas
{
    public partial class AlertaMensaje : Rg.Plugins.Popup.Pages.PopupPage
    {
        public AlertaMensaje(string texto, string imagenSource, string descripcion = "volver al formulario")
        {
            InitializeComponent();

            mensajeTexto.Text = texto;
            imagenTipo.Source = imagenSource;

            //vamos a poner mensajes en los botones
            btnOpcion.Text = descripcion;
        }

        public async void RegresarHome(object sender, EventArgs e)
        {
            await Navigation.PushAsync(new MainPage());
            await PopupNavigation.Instance.PopAsync();
        }

        public async void VolverFormulario(object sender, EventArgs e)
        {
            //tenemos que eliminar en este caso nuestra ventana emergente.
            await PopupNavigation.Instance.PopAsync();
        }
    }
}