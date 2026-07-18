<?php
/**
 * @package     Joomla.Administrator
 * @subpackage  com_docshop
 * @copyright   (c) 2026. All rights reserved.
 * @license     GNU General Public License v3.0
 */

defined('_JEXEC') or die;

class DocshopControllerInvoice extends JControllerLegacy
{
    /**
     * Generate and stream a PDF invoice.
     * URL: index.php?option=com_docshop&task=invoice.generate&id=X
     */
    public function generate()
    {
        $app = JFactory::getApplication();
        $id  = $app->input->getInt('id', 0);

        if (!$id) {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=orders', false),
                'Invalid order ID.',
                'error'
            );
            return;
        }

        // Load order
        JModelLegacy::addIncludePath(JPATH_COMPONENT . '/models');
        /** @var DocshopModelOrder $model */
        $model = JModelLegacy::getInstance('Order', 'DocshopModel');
        $order = $model->getItem($id);

        if (!$order) {
            $app->redirect(
                JRoute::_('index.php?option=com_docshop&view=orders', false),
                'Order not found.',
                'error'
            );
            return;
        }

        // Load TCPDF from RSForm
        $tcpdfPath = JPATH_ADMINISTRATOR . '/components/com_rsform/helpers/tcpdf/tcpdf.php';
        if (!file_exists($tcpdfPath)) {
            $app->enqueueMessage('TCPDF library not found.', 'error');
            $app->redirect(JRoute::_('index.php?option=com_docshop&view=order&id=' . $id, false));
            return;
        }
        require_once $tcpdfPath;

        // ----------------------------------------------------------------
        // Build PDF
        // ----------------------------------------------------------------
        $pdf = new TCPDF('P', 'mm', 'A4', true, 'UTF-8', false);
        $pdf->SetCreator('DocShop');
        $pdf->SetAuthor(JFactory::getConfig()->get('sitename'));
        $pdf->SetTitle('Invoice ' . $order->order_number);
        $pdf->SetSubject('Order Invoice');
        $pdf->setPrintHeader(false);
        $pdf->setPrintFooter(false);
        $pdf->SetMargins(20, 20, 20);
        $pdf->SetAutoPageBreak(true, 20);
        $pdf->AddPage();

        // Colours
        $blue  = array(30, 58, 138);
        $light = array(241, 245, 251);
        $muted = array(107, 114, 128);
        $black = array(17, 24, 39);

        $statusColor = array(
            'completed' => array(16, 185, 129),
            'pending'   => array(245, 158, 11),
            'failed'    => array(239, 68, 68),
            'refunded'  => array(107, 114, 128),
        );
        $sc = isset($statusColor[$order->status]) ? $statusColor[$order->status] : $statusColor['refunded'];

        $siteName = JFactory::getConfig()->get('sitename', 'DocShop');
        $pageW    = $pdf->getPageWidth() - 40;

        // ---- Header band ----
        $pdf->SetFillColor($blue[0], $blue[1], $blue[2]);
        $pdf->Rect(20, 20, $pageW, 22, 'F');

        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 18);
        $pdf->SetXY(24, 24);
        $pdf->Cell($pageW / 2, 10, $siteName, 0, 0, 'L');

        $pdf->SetFont('helvetica', 'B', 11);
        $pdf->SetXY(24, 33);
        $pdf->Cell($pageW - 8, 6, 'INVOICE', 0, 0, 'R');

        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $pdf->SetXY(20, 48);

        // ---- Meta row: Order # | Date | Status ----
        $col = $pageW / 3;

        $pdf->SetFont('helvetica', '', 8);
        $pdf->SetTextColor($muted[0], $muted[1], $muted[2]);
        $pdf->Cell($col, 5, 'ORDER NUMBER', 0, 0, 'L');
        $pdf->Cell($col, 5, 'DATE',         0, 0, 'L');
        $pdf->Cell($col, 5, 'STATUS',       0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $pdf->Cell($col, 6, $order->order_number, 0, 0, 'L');
        $pdf->Cell($col, 6, date('d M Y', strtotime($order->created)), 0, 0, 'L');

        $pdf->SetTextColor($sc[0], $sc[1], $sc[2]);
        $pdf->Cell($col, 6, strtoupper($order->status), 0, 1, 'L');

        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $pdf->Ln(4);

        // ---- Divider ----
        $pdf->SetDrawColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(20, $pdf->GetY(), 20 + $pageW, $pdf->GetY());
        $pdf->Ln(5);

        // ---- Bill To / Payment Info ----
        $halfW  = $pageW / 2 - 4;
        $startY = $pdf->GetY();

        // Bill To
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetXY(20, $startY);
        $pdf->Cell($halfW, 6, 'BILL TO', 0, 1, 'L');

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $pdf->SetX(20);
        $pdf->Cell($halfW, 6, $order->user_name, 0, 1, 'L');

        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetTextColor($muted[0], $muted[1], $muted[2]);
        $pdf->SetX(20);
        $pdf->Cell($halfW, 5, $order->user_email, 0, 1, 'L');
        $pdf->SetX(20);
        $pdf->Cell($halfW, 5, 'Username: ' . $order->user_username, 0, 1, 'L');

        $billEndY = $pdf->GetY();

        // Payment Info
        $rightX = 20 + $halfW + 8;
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetTextColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetXY($rightX, $startY);
        $pdf->Cell($halfW, 6, 'PAYMENT INFO', 0, 1, 'L');

        $infoRows = array(
            array('Method',   ucfirst($order->payment_method ?: 'PayPal')),
            array('Currency', $order->currency ?: 'USD'),
        );
        if (!empty($order->paypal_transaction_id)) {
            $txn = $order->paypal_transaction_id;
            $infoRows[] = array('Transaction', strlen($txn) > 28 ? substr($txn, 0, 28) . '...' : $txn);
        }
        foreach ($infoRows as $row) {
            $pdf->SetFont('helvetica', 'B', 9);
            $pdf->SetTextColor($muted[0], $muted[1], $muted[2]);
            $pdf->SetX($rightX);
            $pdf->Cell($halfW / 2, 5, $row[0] . ':', 0, 0, 'L');
            $pdf->SetFont('helvetica', '', 9);
            $pdf->SetTextColor($black[0], $black[1], $black[2]);
            $pdf->Cell($halfW / 2, 5, $row[1], 0, 1, 'L');
        }

        $pdf->SetY(max($billEndY, $pdf->GetY()) + 6);

        // ---- Items table ----
        $colDesc  = $pageW * 0.50;
        $colVer   = $pageW * 0.15;
        $colQty   = $pageW * 0.10;
        $colPrice = $pageW * 0.125;
        $colTotal = $pageW - $colDesc - $colVer - $colQty - $colPrice;

        // Table header
        $pdf->SetFillColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetTextColor(255, 255, 255);
        $pdf->SetFont('helvetica', 'B', 9);
        $pdf->SetX(20);
        $pdf->Cell($colDesc,  7, 'DESCRIPTION', 0, 0, 'L', true);
        $pdf->Cell($colVer,   7, 'VERSION',      0, 0, 'C', true);
        $pdf->Cell($colQty,   7, 'QTY',          0, 0, 'C', true);
        $pdf->Cell($colPrice, 7, 'UNIT PRICE',   0, 0, 'R', true);
        $pdf->Cell($colTotal, 7, 'TOTAL',        0, 1, 'R', true);

        // Table row
        $pdf->SetFillColor($light[0], $light[1], $light[2]);
        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $pdf->SetFont('helvetica', '', 9);
        $pdf->SetX(20);
        $pdf->Cell($colDesc,  7, $order->document_title,                    0, 0, 'L', true);
        $pdf->Cell($colVer,   7, $order->document_version ?: '-',           0, 0, 'C', true);
        $pdf->Cell($colQty,   7, '1',                                       0, 0, 'C', true);
        $pdf->Cell($colPrice, 7, number_format((float) $order->amount, 2),  0, 0, 'R', true);
        $pdf->Cell($colTotal, 7, number_format((float) $order->amount, 2),  0, 1, 'R', true);

        $pdf->Ln(2);

        // ---- Totals ----
        $totX = 20 + $colDesc + $colVer + $colQty;

        $pdf->SetDrawColor(220, 220, 220);
        $pdf->SetLineWidth(0.3);
        $pdf->Line(20, $pdf->GetY(), 20 + $pageW, $pdf->GetY());
        $pdf->Ln(3);

        $pdf->SetFont('helvetica', 'B', 10);
        $pdf->SetTextColor($black[0], $black[1], $black[2]);
        $pdf->SetX($totX);
        $pdf->Cell($colPrice, 7, 'TOTAL', 0, 0, 'R');
        $pdf->SetTextColor($blue[0], $blue[1], $blue[2]);
        $pdf->Cell($colTotal, 7,
            ($order->currency ?: 'USD') . ' ' . number_format((float) $order->amount, 2),
            0, 1, 'R'
        );

        $pdf->Ln(10);

        // ---- Footer note ----
        $pdf->SetDrawColor($blue[0], $blue[1], $blue[2]);
        $pdf->SetLineWidth(0.5);
        $pdf->Line(20, $pdf->GetY(), 20 + $pageW, $pdf->GetY());
        $pdf->Ln(4);

        $pdf->SetFont('helvetica', 'I', 8);
        $pdf->SetTextColor($muted[0], $muted[1], $muted[2]);
        $pdf->SetX(20);
        $pdf->MultiCell($pageW, 5,
            'Thank you for your purchase. This invoice was generated automatically by ' . $siteName . '.' . "\n"
            . 'For support, please contact us through the website.',
            0, 'C'
        );

        // ---- Stream PDF ----
        $filename = 'invoice-' . preg_replace('/[^A-Za-z0-9\-]/', '-', $order->order_number) . '.pdf';
        while (ob_get_level()) {
            ob_end_clean();
        }
        $pdf->Output($filename, 'D');
        jexit();
    }
}
