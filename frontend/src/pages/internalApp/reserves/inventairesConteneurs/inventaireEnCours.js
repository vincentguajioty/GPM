import React, {useEffect, useState} from 'react';
import { useParams } from 'react-router-dom';
import { Row, Col, Card, Tabs, Tab, Alert, } from 'react-bootstrap';
import PageHeader from 'components/common/PageHeader';
import LoaderInfiniteLoop from 'components/loaderInfiniteLoop';
import WidgetSectionTitle from 'components/widgets/WidgetSectionTitle';
import moment from 'moment-timezone';

import HabilitationService from 'services/habilitationsService';
import { Axios } from 'helpers/axios';
import socketIO from 'socket.io-client';

import InventaireParcoursManuel from './parcoursManuel';
import { Link } from 'react-router-dom';

const socket = socketIO.connect(window.__ENV__.APP_BACKEND_URL,{withCredentials: true, extraHeaders: {
    "token": HabilitationService.token
}});

const ReserveInventaireEnCours = () => {
    let {idReserveInventaire} = useParams();
    const [readyToDisplay, setReadyToDisplay] = useState(false);
    const [isClosed, setIsClosed] = useState(false);
    const [displaySocketError, setDisplaySocketError] = useState(false);
    const [idConteneur, setIdConteneur] = useState();

    const [demandePopullationPrecedente, setDemandePopullationPrecedente] = useState(false);

    const [detailsInventaire, setDetailsInventaire] = useState([]);
    const [inventaireElements, setInventaireElements] = useState([]);
    const [catalogueCodesBarres, setCatalogueCodesBarres] = useState([]);

    const initPageFirstCharge = async () => {
        try {
            const getInventaireDetails = await Axios.post('/reserves/getOneInventaireForDisplay',{
                idReserveInventaire: idReserveInventaire,
            });
            setDetailsInventaire(getInventaireDetails.data.inventaire)
            setIsClosed(!getInventaireDetails.data.inventaire.inventaireEnCours);
            setIdConteneur(getInventaireDetails.data.inventaire.idConteneur)

            const getInventaireElements = await Axios.post('/reserves/getAllElementsInventaireEnCours',{
                idReserveInventaire: idReserveInventaire,
            });
            setInventaireElements(getInventaireElements.data);

            const getCatalogueCodeBarres = await Axios.get('/select/getCodesBarreCatalogue');
            setCatalogueCodesBarres(getCatalogueCodeBarres.data);
            
            setReadyToDisplay(true);

            socket.emit("reserve_inventaire_join", 'reserve-'+idReserveInventaire);
        } catch (error) {
            console.log(error)
        }
    }
    useEffect(()=>{
        initPageFirstCharge();
    },[])

    useEffect(() => {
        socket.on("reserve_inventaire_updateYourElement", (data)=>{
            let tempArray = [];
            for(const elem of inventaireElements)
            {
                if(elem.idReserveElement == data.idReserveElement)
                {
                    tempArray.push(data);
                }else{
                    tempArray.push(elem)
                }
            }
            setInventaireElements(tempArray);
        })

        socket.on("reserve_inventaire_demandePopullationPrecedente", (data)=>{
            setDemandePopullationPrecedente(data);
            location.reload();
        })

        socket.on("reserve_inventaire_validate", (data)=>{
            setIsClosed(true);
        })

        socket.on("connect_error", (error)=>{
            console.log(error);
            setDisplaySocketError(!socket.connected)
        })
	}, [socket, inventaireElements])

    useEffect(()=>{
        if(demandePopullationPrecedente)
        {
            socket.emit("reserve_inventaire_demandePopullationPrecedente", {idReserveInventaire: idReserveInventaire, demandePopullationPrecedente: demandePopullationPrecedente});
            location.reload();
        }
    },[demandePopullationPrecedente])

    const validerInventaire = async (commentaire) => {
        try {
            socket.emit("reserve_inventaire_validate", {idReserveInventaire: idReserveInventaire, commentaire: commentaire||null});
            setIsClosed(true);
        } catch (e) {
            console.log(e);
        }
    }

    if(readyToDisplay)
    {
        return (<>
            <PageHeader
                preTitle="Réserves"
                title={"Inventaire en cours sur "+detailsInventaire.libelleConteneur}
                description={moment(detailsInventaire.dateInventaire).format('DD/MM/YYYY') + " par " + detailsInventaire.prenomPersonne + " " + detailsInventaire.nomPersonne}
                className="mb-3"
            />

            {displaySocketError ?
                <Alert variant='danger'>La connexion au serveur est perdue.</Alert>
            : null}

            {!HabilitationService.habilitations['reserve_modification'] ?
                <Alert variant='warning'>De part vos habilitations, vous ne pouvez pas participer à l'inventaire, mais pouvez le consulter.</Alert>
            : null}

            {isClosed ?
                <Alert variant='success'>
                    Inventaire clos. Vous pouvez revenir au conteneur <Link to={'/reservesConteneurs/'+detailsInventaire.idConteneur}>{detailsInventaire.libelleConteneur}</Link>
                </Alert>
            :
                <InventaireParcoursManuel
                    idConteneur={idConteneur}
                    idReserveInventaire={idReserveInventaire}
                    inventaireElements={inventaireElements}
                    catalogueCodesBarres={catalogueCodesBarres}
                    demandePopullationPrecedente={demandePopullationPrecedente}
                    setDemandePopullationPrecedente={setDemandePopullationPrecedente}
                    validerInventaire={validerInventaire}
                />
            }
        </>);
    }
    else
    {
        return(<LoaderInfiniteLoop/>)
    }
};

ReserveInventaireEnCours.propTypes = {};

export default ReserveInventaireEnCours;
