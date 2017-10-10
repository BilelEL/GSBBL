<h3>Visiteur à sélectionner : </h3>
    <form action="index.php?uc=validerFrais&action=voirFicheFrais" method="post">
        <input type="hidden" name="mois" value="<?php echo $moisAChoisir ?>" />
        <div class="corpsForm">

            <p>

                <label for="lstVisiteur" accesskey="n">Mois : </label>
                <select id="listVisiteur" name="lstVisiteur">
                    <?php
                    foreach ($lesVisiteurs as $leVisiteur) {
                        $idVisiteur = $leVisiteur['idVisiteur'];
                        $nom = $leVisiteur['nom'];
                        $prenom = $leVisiteur['prenom'];
                        if ($idVisiteur == $visiteurAChoisir) {
                            ?>
                            <option selected value="<?php echo $idVisiteur ?>"><?php echo $nom . "/" . $prenom ?> </option>
                            <?php
                        } else {
                            ?>
                            <option value="<?php echo $idVisiteur ?>"><?php echo $nom . "/" . $prenom ?> </option>
                            <?php
                        }
                    }
                    ?>    

                </select>
            </p>
        </div>
        <div class="piedForm">    
            <p>
                <input id="ok" type="submit" value="Valider" size="20" />
                <input id="annuler" type="reset" value="Effacer" size="20" />
            </p> 
        </div>

    </form>

