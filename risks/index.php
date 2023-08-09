<?php
include_once("../controller/dependent.php");
//if(isset($_SESSION['__lwf__'])){
//    header("location: ../dashboard");
//    exit;
//}
?>
<?php 
    $title ='Risks  | '.$site_name;
    $keyword ='Risks ,Risks  at '.$site_name.', what we offer, '.$site_link.',';
    $discription = ''; 
    $home = '';
    $about = '';
    $services = '';
    $acct = '';
    $pricing = '';
    $faq = 'active';
    $contact = '';
    include_once '../temp_header.php'; 
?>
<div class="page-title-area">
            <div class="d-table">
                <div class="d-table-cell">
                    <div class="container">
                        <div class="title-content">
                            <h2>Risks </h2>
                            <ul>
                                <li>
                                    <a href="../">Home</a>
                                </li>
                                <li>
                                    <span>Risks </span>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="service-details-area ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8">
                        <div class="details-item">
                            <div class="details-img">
                                <img src="../assets/img/strategy/risk.jpg" alt="Risks " style="margin-bottom: 50px" />
                                <h3>Risks  </h3>
                                <div class="elementor-widget-container">
                                    <p><strong>What happens to my assets after they are transferred to Green Earth and how does Green Earth manage the related risks?</strong></p>
                                    <p>
                                        <strong><?php echo $site_name_abbr2;?> Wallet</strong><br />
                                        We provide clients with the ability to store supported digital assets in a non-interest bearing 
                                        account (the “Wallet”) that also facilitates their ability to use certain of our products and services, 
                                        such as trading digital assets
                                        through our platform, receiving U.S. dollar loans and receiving digital asset rewards through 
                                        our <?php echo $site_name_abbr2;?> Rewards Visa® Signature Card. 
                                        Assets transferred to a Wallet account are not deployed for our revenue-generating
                                        activities and are held securely in wallets we control or with our custodial partners.
                                    </p>
                                    <p>
                                        <strong><?php echo $site_name_abbr2;?> Interest Account (FAIA)and <?php echo $site_name_abbr2;?> Personalized Yield (FAPY)</strong><br />
                                        We borrow digital assets from clients pursuant to individually negotiated borrowing arrangements that are negotiated to accommodate a client’s objectives, including through Green Earth Personalized Yield (“FAPY”).<br />
                                        Digital assets transferred to us through FAIA or FAPY or other types of individually negotiated borrowing arrangements are used for our revenue-generating activities in the digital asset markets, which currently consist primarily of
                                        activities relating to lending to our institutional and retail clients and facilitating digital asset trading on behalf of our institutional and retail clients. Specifically, we may, in our discretion, pledge, repledge, hypothecate,
                                        rehypothecate, sell, lend, or otherwise transfer, invest or use any amount of digital assets transferred to us, separately or together with other property, with all attendant rights of ownership, and for any period of time and
                                        without retaining in our possession and/or control a like amount of digital assets. We store digital assets that we have not yet deployed by using various custodians, exchanges, and wallet providers.<br />
                                        Green Earth’s core value is Transparency Builds Trust – maintaining and expanding our clients’ trust is paramount to that. As such, we view risk management as key to our success. We seek to monitor and control our risk exposure
                                        through an enterprise risk management framework, including by managing liquidity and credit risks that could potentially impact our obligations to clients that have a FAIA, or with whom we have a FAPY or other type of individually
                                        negotiated borrowing arrangement.
                                    </p>
                                    <p><strong>Liquidity Risk</strong></p>
                                    <p>
                                        We seek to maintain the liquidity necessary to meet all our obligations under our core business activities, which include institutional and retail borrowing and trading activities. We seek to maintain sufficient levels of short-term
                                        assets to meet client redemption and payback obligations, as well as to support trading activity, by keeping sufficient balances in inventory.<br />
                                        We have established the following guidelines to manage our liquidity risks and available balances of short-term assets:
                                    </p>
                                    <p>
                                        * We will hold at least 10% of total amounts due to clients upon demand in inventory, ready to be returned to clients.<br />
                                        * We aim to hold at least 50% of total amounts due to clients upon demand either in inventory or in loans that can be called within seven calendar days.<br />
                                        * We aim to hold at least 90% of total amounts due to clients upon demand either in inventory, or loans that can be called back within one year.
                                    </p>
                                    <p>As of December 31, 2021, the fair value of the stable coin and other digital assets on our FAIA and our individually negotiated borrowing platforms was approximately $10.1 billion, and of this amount approximately:</p>
                                    <p>
                                        * 53% were lent out by us to institutional and retail borrowers;<br />
                                        * 41% were held in inventory with third-party custodians and multi-party-computation wallets and accounts;<br />
                                        * 3% were held with banks and brokers in the form of cash or securities; and<br />
                                        * 3% were deployed as investments, posted as collateral or held as other assets.
                                    </p>
                                    <p>
                                        We manage digital asset conversion risk by seeking to match liabilities with corresponding digital assets held on hand or deploying digital assets into loans or investments that will generally generate returns in the same
                                        denomination of the corresponding liabilities.
                                    </p>
                                    <p><strong>Credit Risk</strong></p>
                                    <p>
                                        As of December 31, 2021, the fair market value of our outstanding loans to borrowers was approximately $5.4 billion. We require many, but not all, borrowers to post varying levels of collateral depending on the borrower’s credit
                                        profile and the size of the loan portfolio. As of December 31, 2021, our net exposure was approximately $1.7 billion. We define net exposure as the sum of our net exposures to individual loan counterparties. Our net exposure to each
                                        individual loan counterparty equals the fair market value of loans to the counterparty minus the fair market value of the collateral provided by the counterparty (excluding any amount of the counterparty’s collateral that is in
                                        excess of the counterparty’s loans). The average term of our loan portfolio is less than one year.
                                    </p>
                                    <p>
                                        <strong>Institutional Loan Portfolio</strong><br />
                                        We enable institutional clients such as hedge funds, market makers, proprietary trading firms, over-the-counter trading desks, and corporations such as exchanges and digital assets miners to obtain digital assets or U.S. dollar
                                        financing from us through negotiated loan agreements. Interest on these loans is typically fixed and payable in kind, and the average term of these loans is less than one year.<br />
                                        As of December 31, 2021, the fair market value of our outstanding loans to institutional borrowers was approximately $4.4 billion. Each institutional client that borrows from us undergoes a credit due diligence process to allow our
                                        credit risk underwriting team to establish appropriate credit limits. We have established an internal credit lending policy that generally limits exposure to any one borrower, principal or guarantor based on net exposures, which
                                        represents our aggregate exposure to economically related borrowers for approval purposes. Based on our internal credit process, our loan approvals follow a transaction authority and credit limit matrix, which are based on a
                                        counterparty’s financial information and size, business model related to traditional or digital assets markets, country of domicile, leverage, and other credit measures.<br />
                                        We require many, but not all, institutional borrowers to post collateral in the form of digital assets, cash or other assets. Whether we require institutional borrowers to post collateral and, if so, the type and level of collateral
                                        we require, depends on the borrower’s credit profile and the size and composition of the loan portfolio. The collateral provided by our institutional borrower clients may also be subject to margin calls if the loan to collateral
                                        value ratio breaches certain thresholds set forth in their loan agreements.
                                    </p>
                                    <p>
                                        <strong>Retail Loan Portfolio</strong><br />
                                        We provide retail clients access to U.S. dollar and stable coin loans secured by digital asset collateral. We determine the interest rates for these loans based on the level of over-collateralization and the type of digital asset
                                        collateral. Interest on these loans is generally payable on a monthly basis, with the principal due at maturity. Loans typically mature in one year, subject to our clients’ option to prepay without penalty.<br />
                                        As of December 31, 2021, the fair market value of our outstanding loans to retail borrowers was approximately $1.0 billion. We typically allow retail clients to borrow funds with a value of up to 50% of their collateral. Given the
                                        volatility in digital asset markets, the collateral provided by our clients is subject to margin calls. If the value of a digital asset decreases significantly, such that the loan to value ratio is above a specified threshold, we
                                        will make a margin call to the client. If the client does not meet the margin call, we may liquidate a portion of the collateral and reduce the amount of the outstanding loan. If the loan to value ratio increases to an accelerated
                                        threshold at any time, we are authorized, without providing client notice, to liquidate the collateral to reduce the amount of the outstanding loan. Conversely, if collateral values increase, we may allow borrowers, upon request, to
                                        remove excess collateral. If a client defaults on repayment, we may liquidate an amount of collateral held for the client up to the value of the loan plus all additional amounts owed by that client.
                                    </p>
                                </div>

                            </div>                            
                        </div>
                    </div>
                    <div class="col-lg-4">
                        <div class="widget-area">
                            <div class="services widget-item">
                                <h3>Services List</h3>
                                <ul>
                                    <li>
                                        <a href="../services-forex/">
                                            Forex Trading
                                            <i class='bx bx-right-arrow-alt'></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="../services-stocks/">
                                            Stock & Commodities
                                            <i class='bx bx-right-arrow-alt'></i>
                                        </a>
                                    </li>
                                    <li>
                                        <a href="../services-crypto/">
                                            Cryptocurrency Investment
                                            <i class='bx bx-right-arrow-alt'></i>
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="download widget-item">
                                <h3>Download</h3>
                                <ul>
                                    <li>
                                        <a href="../assets/cert.pdf">
                                            <i class='bx bxs-file-pdf'></i>
                                            Certificate pdf
                                        </a>
                                    </li>
                                    <li style=" display:  none">
                                        <a href="#">
                                        <i class='bx bx-notepad'></i>
                                        Wordfile.doc
                                        </a>
                                    </li>
                                </ul>
                            </div>
                            <div class="consultation">
                                <img src="../assets/img/services/service-details4.jpg" alt="Details">
                                <div class="inner">
                                    <h3>Need Consultation?</h3>
                                    <a class="common-btn" href="../contact/">
                                        Send Message
                                        <span></span>
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
<?php include_once '../temp_footer.php'; ?>
</body>

</html>