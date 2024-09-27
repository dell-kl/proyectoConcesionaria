using System;
using System.Collections.Generic;
using System.Text;
using System.Windows.Input;
using Xamarin.Forms;
using Concesionaria.Views;

namespace Concesionaria.ModelViews
{
    class MainPageViewModel
    {
        public ICommand menu { set; get; }

        public MainPageViewModel() {
            menu = new Command<string>(menuEvent);
        }

        public void menuEvent(string parametro)
        {
            switch (parametro)
            {
                case "clientes":
                    App.Current.MainPage.Navigation.PushAsync(new ClienteRegistro());
                    break;

                case "vehiculos":
                    App.Current.MainPage.Navigation.PushAsync(new AutoRegistro());
                    break;

                case "asignarVehiculo":
                    App.Current.MainPage.Navigation.PushAsync(new AsignarVehiculoRegistro());
                    break;

                case "verificarAsignacion":
                    App.Current.MainPage.Navigation.PushAsync(new VerificarAsignacion());
                    break;
            }
        }

    }
}
