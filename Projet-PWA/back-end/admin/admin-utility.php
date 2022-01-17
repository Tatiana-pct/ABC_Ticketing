<?php
//-------------Fonctions utilitaires-------------
/**
 * Function allowing to display the header of the table mails
 */
function tableHeadMails() {
    $column_action = intval(unserialize($_SESSION['user'])->getAdmin()) === 1 ? "<th>Action</th>" : "";
    echo "<tr class='form_tr1'>
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Entreprise</th>
            <th>Site Web</th>
            <th>Objet</th>
            <th>Message</th>
            <th>SpeedCall</th>
            <th>Jour de rappel</th>
            <th>Heure de rappel</th>
            <th>Demande faite le </th>
            $column_action
          </tr>";
}

/**
 * Function allowing the display of a row of query mails result
 *
 * @param Mail $mail
 */
function tableLineMails (Mail $mail): void
{
    $delete = "href='../back-end/admin/admin-mail-treatment.php?id=".$mail->getId()."&delete=1'";
    $btn_delete = intval(unserialize($_SESSION['user'])->getAdmin()) === 1 ? "<td><a class='btn-action' title='Supprimer cette demande' ".$delete."><i class='fas fa-trash-alt'></i></a></td>" : "";
    $speedcall = $mail->isSpeedCall() ? 'Oui' : 'Non';
    echo "<tr class='form_tr_result'>
                <td>".$mail->getUser()->getLastname()."</td>
                <td>".$mail->getUser()->getFirstname()."</td>
                <td>".$mail->getUser()->getEmail()."</td>
                <td>".$mail->getUser()->getPhone()."</td>
                <td>".$mail->getUser()->getEnterprise()->getName()."</td>
                <td>".$mail->getWebsite()."</td>
                <td>".$mail->getObject()."</td>
                <td>".$mail->getMessage()."</td>
                <td>".$speedcall."</td>
                <td>".$mail->getSpeedCallDate()."</td>
                <td>".$mail->getSpeedCallHours()."</td>
                <td>".$mail->getSendDate()->format("Y/m/d H:i:s")."</td>
                $btn_delete
           </tr>";
}

/**
 * Function allowing to display the header of the table users
 */
function tableHeadUsers() {
    echo '<tr class="form_tr1">
            <th>Nom</th>
            <th>Prénom</th>
            <th>Email</th>
            <th>Téléphone</th>
            <th>Numéro client</th>
            <th>Entreprise</th>
            <th>Siret</th>
            <th>Administrateur</th>
            <th>Bloqué</th>
            <th>Actions</th>
          </tr>';
}

/**
 * Function allowing the display of a row of query users result
 *
 * @param User $user
 */
function tableLineUsers (User $user): void
{
    $view = "href='../back-end/profil/profil-treatment.php?id=".$user->getId()."&view=1'";
    $udpate = "href='../back-end/profil/profil-treatment.php?id=".$user->getId()."'";
    $block = "href='../back-end/admin/admin-user-treatment.php?id=".$user->getId()."&blockRequest=1'";
    $delete = "href='../back-end/admin/admin-user-treatment.php?id=".$user->getId()."&delete=1'";
    $is_admin = $user->getAdmin() === 1 ? 'Oui' : 'Non';
    $is_blocked = $user->getBlocked() === 1 ? 'Oui' : 'Non';
    $block_title = $user->getBlocked() === 0 ? "Bloquer cet utilisateur" : "Débloquer cet utilisateur";
    $block_icon = $user->getBlocked() === 0 ? "<i class='fas fa-user-alt-slash'></i>" : "<i class='fas fa-user-alt'></i>";
    echo "<tr class='form_tr_result'>
                <td>".$user->getLastname()."</td>
                <td>".$user->getFirstname()."</td>
                <td>".$user->getEmail()."</td>
                <td>".$user->getPhone()."</td>
                <td>".$user->getEnterprise()->getClientNumber()."</td>
                <td>".$user->getEnterprise()->getName()."</td>
                <td>".$user->getEnterprise()->getSiret()."</td>
                <td>".$is_admin."</td>
                <td>".$is_blocked."</td>
                <td class='action'>"
                    ."<a class='btn-action' title='Voir le profil'".$view."><i class='fas fa-info-circle'></i></a>"
                    ."<a class='btn-action' title='Editer le profil'".$udpate."><i class='fas fa-edit'></i></a>"
                    ."<a class='btn-action' title='$block_title'".$block.">$block_icon</a>"
                    ."<a class='btn-action' title='Supprimer cet utilisateur'".$delete."><i class='fas fa-trash-alt'></i></a></td>
           </tr>";
}


