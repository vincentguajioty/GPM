import HabilitationService from 'services/habilitationsService';
import ConfigurationService from 'services/configurationService';

export const publicRoutes = {
    label: 'Accès libre',
    labelDisable: false,
    children: [
        {
            name: 'Accueil',
            to: '/',
            icon: 'home',
            exact: true,
            active: true
        }
    ]
};

if(ConfigurationService.appname)
{
    if(ConfigurationService.consommation_benevoles == 1)
    {
        publicRoutes.children.push(
            {
                name: 'Tracer une consommation',
                to: '/consoPublic',
                icon: 'notes-medical',
                exact: true,
                active: true
            }
        )
    }

    if(ConfigurationService.alertes_benevoles_lots == 1 || ConfigurationService.alertes_benevoles_vehicules == 1)
    {
        publicRoutes.children.push(
            {
                name: 'Déclarer un incident',
                to: '/incidentPublic',
                icon: 'exclamation-triangle',
                exact: true,
                active: true
            }
        )
    }
}

export const loginRoutes = {
    label: 'Equipe Logistique',
    labelDisable: false,
    children: [
        {
            name: HabilitationService.habilitations ? 'Accéder à l\'interface' : 'Se connecter',
            to: HabilitationService.habilitations ? '/home' : '/login',
            icon: 'user',
            exact: true,
            active: true
        }
    ]
};
  
export default [
    publicRoutes,
    loginRoutes,
];
  