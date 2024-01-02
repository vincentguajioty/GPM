import React, { useState, useEffect } from 'react';
import LoaderInfiniteLoop from 'components/loaderInfiniteLoop';
import SoftBadge from 'components/common/SoftBadge';
import GPMtable from 'components/gpmTable/gpmTable';
import moment from 'moment-timezone';

import { Axios } from 'helpers/axios';
import HabilitationService from 'services/habilitationsService';

import ReservesMaterielsForm from './materielsForm';
import ReservesMaterielsDeleteModal from './materielsDeleteModal';

const ReservesMaterielsTable = ({
    displayLibelleMateriel = true,
    displayLibelleConteneur = true,
    displayQuantiteReserve = true,
    displayPeremptionReserve = true,
    displayActions = true,
    filterIdConteneur = null,
}) => {
    const [pageNeedsRefresh, setPageNeedsRefresh] = useState(false);
    
    const [readyToDisplay, setReadyToDisplay] = useState(false);
    const [materiels, setMateriels] = useState([]);

    const initPage = async () => {
        try {
            const getData = await Axios.post('/reserves/getReservesMateriels',{
                filterIdConteneur: filterIdConteneur
            });
            setMateriels(getData.data);  
            
            setReadyToDisplay(true);
        } catch (error) {
            console.log(error)
        }
    }

    useEffect(() => {
        initPage();
    }, [])
    useEffect(() => {
        if(pageNeedsRefresh)
        {
            setPageNeedsRefresh(false);
            initPage();
        }
    }, [pageNeedsRefresh])

    const colonnes = [
        {
            accessor: 'libelleMateriel',
            Header: 'Libellé',
            isHidden: !displayLibelleMateriel,
        },
        {
            accessor: 'libelleConteneur',
            Header: 'Conteneur',
            isHidden: !displayLibelleConteneur,
        },
        {
            accessor: 'quantiteReserve',
            Header: 'Quantité',
            isHidden: !displayQuantiteReserve,
            Cell: ({ value, row }) => {
				return(
                    row.original.quantiteReserve < row.original.quantiteAlerteReserve ?
                        <SoftBadge bg='danger'>{row.original.quantiteReserve}</SoftBadge>
                    :
                        row.original.quantiteReserve == row.original.quantiteAlerteReserve ?
                            <SoftBadge bg='warning'>{row.original.quantiteReserve}</SoftBadge>
                        :
                            <SoftBadge bg='success'>{row.original.quantiteReserve}</SoftBadge>
                );
			},
        },
        {
            accessor: 'peremptionReserve',
            Header: 'Péremption',
            isHidden: !displayPeremptionReserve,
            Cell: ({ value, row }) => {
				return(
                    row.original.peremptionReserve != null ?
                        row.original.peremptionReserve < new Date() ?
                            <SoftBadge bg='danger'>{moment(row.original.peremptionReserve).format('DD/MM/YYYY')}</SoftBadge>
                        :
                            row.original.peremptionNotificationReserve < new Date() ?
                                <SoftBadge bg='warning'>{moment(row.original.peremptionReserve).format('DD/MM/YYYY')}</SoftBadge>
                            :
                                <SoftBadge bg='success'>{moment(row.original.peremptionReserve).format('DD/MM/YYYY')}</SoftBadge>
                    : null
                );
			},
        },
        {
            accessor: 'actions',
            Header: 'Actions',
            isHidden: !displayActions,
            Cell: ({ value, row }) => {
				return(
                    <>
                        {row.original.inventaireEnCours ?
                            <SoftBadge bg='danger'>INVENTAIRE EN COURS</SoftBadge>    
                        :
                            <>
                                {HabilitationService.habilitations['materiel_modification'] ? 
                                    <ReservesMaterielsForm idReserveElement={row.original.idReserveElement} element={row.original} setPageNeedsRefresh={setPageNeedsRefresh} />
                                : null}
                                {HabilitationService.habilitations['materiel_suppression'] ? 
                                    <ReservesMaterielsDeleteModal idReserveElement={row.original.idReserveElement} setPageNeedsRefresh={setPageNeedsRefresh} />
                                : null}
                            </>
                        }
                    </>
                );
			},
        },
    ];

    return (
    <>
        {readyToDisplay ?
            <GPMtable
                columns={colonnes}
                data={materiels}
                topButtonShow={true}
                topButton={
                    HabilitationService.habilitations['materiel_ajout'] ?
                        <ReservesMaterielsForm setPageNeedsRefresh={setPageNeedsRefresh} />
                    : null
                }
            />
        : <LoaderInfiniteLoop />}
    </>);
};

ReservesMaterielsTable.propTypes = {};

export default ReservesMaterielsTable;
