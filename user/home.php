<?php
include "header.php";
include "../include/conn_db.php";
include "../admin/functions/chiffre.php";
?>


            <!-- Content -->
            <div class="content">
                    <div class="search-container">
                        <i class="fa fa-search search-icon"></i>
                        <input type="search" name="search" id="search" placeholder="Search category ...">
                    </div>   
                                
            <!-- Page Accueil -->
                <!-- <div id="homePage" class="page active">
                    <div class="user-info-card">
                        <div class="user-info-header">
                            <div class="user-info-avatar">JD</div>
                            <div class="user-info-details">
                                <h2>Jean Dupont</h2>
                                <p>jean.dupont@entreprise.com</p>
                                <p>Responsable Marketing</p>
                            </div>
                        </div>
                    </div>

                 </div>  -->
                          <!-- start grid category -->


                                <!-- Display existing categories from database -->
            <!-- <div class="category-card" > -->
              <h3 style="margin-bottom: 2rem; color: var(--gray-900);">Catégories Disponibles <span>category</span></h3> 
             



                                
              <div class="category-card" >
                              <!-- <div class="categories-grid"> -->

     <?php 

                $stmt = $conn->prepare("SELECT * FROM category ORDER BY id");

                $stmt->execute();
                $res = $stmt->get_result();
                $i = 1;
            

                if($res->num_rows >0){
                    while($row = $res->fetch_assoc()){
                    $chiffrement = encryptId($row['id']);
                    $quntiteProduct = $conn->prepare("SELECT COUNT(*) as res FROM produits WHERE category_id = ?");
                    $quntiteProduct->bind_param('i' , $row['id']);
                    $quntiteProduct->execute();
                    $result = $quntiteProduct->get_result()->fetch_assoc();
                    $id = encryptId( $row['id']) ;
            echo "
                <a href='product_category.php?id=$id' style='text-decoration: none'>
                <div class='card_pies'>
                    <div class='category-image category-icon'>
                        <img src='../admin/uploads_category/$row[image]' alt='Category Image' class='category-img'>
                    </div>
                    <div class='text'>
                        <h3>$row[name]</h3>
                        <p class='category-description'>$row[description]</p>
                    </div>

                    <div class='products-count'><span class='result_product'>$result[res]</span> products</div>
                </div>
                </a>
            ";}}

            ?>

        
                    </div>
                </div>

                <!-- Page Produits -->
                <div id="productsPage" class="page">
                    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
                        <h3 style="color: var(--gray-900);">Produits Disponibles</h3>
                        <button class="btn btn-primary" onclick="showCart()" id="cartBtn">
                            🛒 Panier (<span id="cartCount">0</span>)
                        </button>
                    </div>


                    <!-- Panier -->
                    <div class="cart-container" id="cartContainer" style="display: none;">
                        <div class="cart-header">
                            <h3 class="cart-title">Récapitulatif de Commande</h3>
                            <span class="cart-count" id="cartItemCount">0 articles</span>
                        </div>
                        <table class="cart-table" id="cartTable">
                            <thead>
                                <tr>
                                    <th>Produit</th>
                                    <th>Prix Unitaire</th>
                                    <th>Quantité</th>
                                    <th>Total</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="cartTableBody">
                                <!-- Les articles du panier seront générés par JavaScript -->
                            </tbody>
                        </table>
                        <div class="cart-total">
                            <div class="total-row">
                                <span>Sous-total:</span>
                                <span id="subtotal">€0.00</span>
                            </div>
                            <div class="total-row">
                                <span>TVA (20%):</span>
                                <span id="tax">€0.00</span>
                            </div>
                            <div class="total-row final">
                                <span>Total:</span>
                                <span id="total">€0.00</span>
                            </div>
                        </div>
                        <div style="display: flex; gap: 1rem; justify-content: flex-end;">
                            <button class="btn btn-outline" onclick="clearCart()">Vider le Panier</button>
                            <button class="btn btn-success" onclick="confirmOrder()">Confirmer la Commande</button>
                        </div>
                    </div>
                </div>

                <!-- Page Commandes -->
                <div id="ordersPage" class="page">
                    <h3 style="margin-bottom: 2rem; color: var(--gray-900);">Mes Commandes</h3>
                    <div class="orders-table">
                        <table>
                            <thead>
                                <tr>
                                    <th>N° Commande</th>
                                    <th>Date</th>
                                    <th>Produits</th>
                                    <th>Total</th>
                                    <th>Statut</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody id="ordersTableBody">
                                <tr>
                                    <td>#CMD-001</td>
                                    <td>15/12/2024</td>
                                    <td>3 articles</td>
                                    <td>€156.80</td>
                                    <td><span class="status-badge status-confirmed">Confirmée</span></td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.8rem;">
                                            📄 Facture
                                        </button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#CMD-002</td>
                                    <td>12/12/2024</td>
                                    <td>2 articles</td>
                                    <td>€89.50</td>
                                    <td><span class="status-badge status-pending">En attente</span></td>
                                    <td>
                                        <button class="btn btn-outline" style="padding: 0.25rem 0.75rem; font-size: 0.8rem;">
                                            📄 Facture
                                        </button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>

                <!-- Page Profil -->
                <div id="profilePage" class="page">
                    <h3 style="margin-bottom: 2rem; color: var(--gray-900);">Mon Profil</h3>
                    <div class="profile-form">
                        <form>
                            <div class="form-group">
                                <label class="form-label">Nom complet</label>
                                <input type="text" class="form-control" value="Jean Dupont">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Email</label>
                                <input type="email" class="form-control" value="jean.dupont@entreprise.com">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Poste</label>
                                <input type="text" class="form-control" value="Responsable Marketing">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Téléphone</label>
                                <input type="tel" class="form-control" value="+33 1 23 45 67 89">
                            </div>
                            <div class="form-group">
                                <label class="form-label">Département</label>
                                <select class="form-control">
                                    <option>Marketing</option>
                                    <option>Ventes</option>
                                    <option>IT</option>
                                    <option>RH</option>
                                </select>
                            </div>
                            <button type="submit" class="btn btn-primary">Mettre à jour</button>
                        </form>
                    </div>
                </div>
            
      




<?php 

include "footer.php";

?>