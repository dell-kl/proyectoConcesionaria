using Concesionaria.Models;
using Concesionaria.Resource;
using Concesionaria.Views.Alertas;
using Newtonsoft.Json;
using Rg.Plugins.Popup.Services;
using System;
using System.Collections.Generic;
using System.Net.Http;
using System.Net;
using System.Text;
using System.Windows.Input;
using Xamarin.Forms;

namespace Concesionaria.ModelViews
{
    public class AutoRegistroViewModel
    {
        public ICommand registroAutoCommand { set; get; }
        public ICommand Retornar { set; get; }


        public Vehiculo vehiculo { set; get; }

        public AutoRegistroViewModel() {

            this.registroAutoCommand = new Command(registroAutoEvent);
            this.Retornar = new Command(retornarHome);
        }

        public void retornarHome()
        {
            App.Current.MainPage.Navigation.PopAsync();
        }

        public async void registroAutoEvent()
        {
            var data = JsonConvert.SerializeObject(this.vehiculo);
            var respuesta = new StringContent(data, Encoding.UTF8, "application/json");

            using (HttpClient solicitud = new HttpClient()) // -> usamos y cerramos conexion.
            {
                var entrada = await solicitud.PostAsync($"http://{IPv4.ip}/vehiculos/registrar", respuesta);

                //resetear los datos de nusetro modelo unido al formulario
                this.vehiculo = new Vehiculo();

                if (entrada.StatusCode.Equals(HttpStatusCode.OK))
                {
                    await PopupNavigation.Instance.PushAsync(new AlertaMensaje("Vehiculo registrado exitosamente", "check.png"));
                }
                else
                {
                    await PopupNavigation.Instance.PushAsync(new AlertaMensaje("No se logro registrar el vehiculo", "cancelar.png"));
                }
            }
        }

    }
}
