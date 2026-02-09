<?php

namespace App\Controllers\Admin;

use App\Controllers\BaseController;
use App\Models\OrderModel;
use App\Models\OrderDetailModel;
use App\Models\ProductModel;

class ReportController extends BaseController
{
    protected $orderModel;
    protected $orderDetailModel;
    protected $productModel;

    public function __construct()
    {
        $this->orderModel = new OrderModel();
        $this->orderDetailModel = new OrderDetailModel();
        $this->productModel = new ProductModel();
    }

    public function index()
    {
        // Get filter parameters
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');
        
        // Get sales data
        $salesData = $this->getSalesData($startDate, $endDate);
        $topProducts = $this->getTopProducts($startDate, $endDate);
        $salesByStatus = $this->getSalesByStatus($startDate, $endDate);
        $dailySales = $this->getDailySales($startDate, $endDate);

        $data = [
            'title'         => 'Laporan Penjualan',
            'startDate'     => $startDate,
            'endDate'       => $endDate,
            'salesData'     => $salesData,
            'topProducts'   => $topProducts,
            'salesByStatus' => $salesByStatus,
            'dailySales'    => $dailySales,
        ];

        return view('admin/reports/index', $data);
    }

    private function getSalesData($startDate, $endDate)
    {
        // Get all orders for status counting
        $orders = $this->orderModel
            ->where('order_date >=', $startDate)
            ->where('order_date <=', $endDate)
            ->findAll();

        // Get paid orders only for revenue calculation
        $paidOrders = $this->orderModel
            ->where('order_date >=', $startDate)
            ->where('order_date <=', $endDate)
            ->where('payment_status', 'paid')
            ->findAll();

        $totalRevenue = 0;
        $totalOrders = count($orders);
        $paidOrdersCount = count($paidOrders);
        $completedOrders = 0;
        $pendingOrders = 0;
        $cancelledOrders = 0;

        // Calculate revenue only from paid orders
        foreach ($paidOrders as $order) {
            $totalRevenue += (float) $order['amount'];
        }
        
        // Count orders by status (all orders, not just paid)
        foreach ($orders as $order) {
            switch (strtolower($order['order_status'])) {
                case 'completed':
                case 'selesai':
                case 'delivered':
                    $completedOrders++;
                    break;
                case 'pending':
                case 'menunggu':
                    $pendingOrders++;
                    break;
                case 'cancelled':
                case 'dibatalkan':
                    $cancelledOrders++;
                    break;
            }
        }

        return [
            'totalRevenue'    => $totalRevenue,
            'totalOrders'    => $totalOrders,
            'paidOrders'     => $paidOrdersCount,
            'completedOrders' => $completedOrders,
            'pendingOrders'  => $pendingOrders,
            'cancelledOrders' => $cancelledOrders,
            'averageOrder'    => $paidOrdersCount > 0 ? $totalRevenue / $paidOrdersCount : 0,
        ];
    }

    private function getTopProducts($startDate, $endDate)
    {
        // Get only paid orders
        $orderIds = $this->orderModel
            ->select('id')
            ->where('order_date >=', $startDate)
            ->where('order_date <=', $endDate)
            ->where('payment_status', 'paid')
            ->findAll();
        
        $orderIdArray = array_column($orderIds, 'id');
        
        if (empty($orderIdArray)) {
            return [];
        }

        $topProducts = $this->orderDetailModel
            ->select('products.name, SUM(order_details.quantity) as total_quantity, SUM(order_details.price * order_details.quantity) as total_revenue')
            ->join('products', 'products.id = order_details.product_id')
            ->whereIn('order_details.order_id', $orderIdArray)
            ->groupBy('order_details.product_id')
            ->orderBy('total_quantity', 'DESC')
            ->limit(10)
            ->findAll();

        return $topProducts;
    }

    private function getSalesByStatus($startDate, $endDate)
    {
        // Get sales by order status with total amount for each status
        $db = \Config\Database::connect();
        
        $orders = $db->query("
            SELECT 
                order_status, 
                COUNT(*) as count, 
                SUM(amount) as total
            FROM orders
            WHERE order_date >= ? AND order_date <= ?
            GROUP BY order_status
        ", [$startDate, $endDate])->getResultArray();

        return $orders;
    }

    private function getDailySales($startDate, $endDate)
    {
        // Get daily sales, but revenue only from paid orders
        $db = \Config\Database::connect();
        
        $dailySales = $db->query("
            SELECT 
                DATE(order_date) as date, 
                COUNT(*) as orders, 
                SUM(CASE WHEN payment_status = 'paid' THEN amount ELSE 0 END) as revenue
            FROM orders
            WHERE order_date >= ? AND order_date <= ?
            GROUP BY DATE(order_date)
            ORDER BY date ASC
        ", [$startDate, $endDate])->getResultArray();

        return $dailySales;
    }

    public function export()
    {
        $format = $this->request->getGet('format') ?: 'xlsx';
        $startDate = $this->request->getGet('start_date') ?: date('Y-m-01');
        $endDate = $this->request->getGet('end_date') ?: date('Y-m-t');
        
        // Get sales data
        $salesData = $this->getSalesData($startDate, $endDate);
        $topProducts = $this->getTopProducts($startDate, $endDate);
        $salesByStatus = $this->getSalesByStatus($startDate, $endDate);
        $dailySales = $this->getDailySales($startDate, $endDate);
        
        // Get all orders for detail
        $orders = $this->orderModel
            ->select('orders.*, users.full_name as customer_name, users.email as customer_email')
            ->join('users', 'users.id = orders.customer_id')
            ->where('order_date >=', $startDate)
            ->where('order_date <=', $endDate)
            ->orderBy('order_date', 'DESC')
            ->findAll();
        
        if ($format === 'pdf') {
            return $this->exportPDF($startDate, $endDate, $salesData, $topProducts, $salesByStatus, $dailySales, $orders);
        } else {
            return $this->exportExcel($startDate, $endDate, $salesData, $topProducts, $salesByStatus, $dailySales, $orders);
        }
    }
    
    private function exportExcel($startDate, $endDate, $salesData, $topProducts, $salesByStatus, $dailySales, $orders)
    {
        // Check if PhpSpreadsheet is available
        if (!class_exists('\PhpOffice\PhpSpreadsheet\Spreadsheet')) {
            return redirect()->back()->with('error', 'PhpSpreadsheet library tidak ditemukan. Silakan jalankan: composer require phpoffice/phpspreadsheet');
        }

        $filename = 'Laporan_Penjualan_' . $startDate . '_' . $endDate . '.xlsx';
        
        // Create new Spreadsheet object
        $spreadsheet = new \PhpOffice\PhpSpreadsheet\Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        
        // Set document properties
        $spreadsheet->getProperties()
            ->setCreator('E-Commerce System')
            ->setTitle('Laporan Penjualan')
            ->setSubject('Laporan Penjualan')
            ->setDescription('Laporan Penjualan periode ' . $startDate . ' - ' . $endDate);
        
        $row = 1;
        
        // Header
        $sheet->setCellValue('A' . $row, 'LAPORAN PENJUALAN');
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(16);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Periode: ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate)));
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tanggal Export: ' . date('d/m/Y H:i:s'));
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $row += 2;
        
        // Summary
        $sheet->setCellValue('A' . $row, 'RINGKASAN');
        $sheet->mergeCells('A' . $row . ':B' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Total Pendapatan');
        $sheet->setCellValue('B' . $row, $salesData['totalRevenue']);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Total Pesanan');
        $sheet->setCellValue('B' . $row, $salesData['totalOrders']);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Pesanan Selesai');
        $sheet->setCellValue('B' . $row, $salesData['completedOrders']);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Pesanan Pending');
        $sheet->setCellValue('B' . $row, $salesData['pendingOrders']);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Pesanan Dibatalkan');
        $sheet->setCellValue('B' . $row, $salesData['cancelledOrders']);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Rata-rata Pesanan');
        $sheet->setCellValue('B' . $row, $salesData['averageOrder']);
        $sheet->getStyle('B' . $row)->getNumberFormat()->setFormatCode('#,##0');
        $row += 2;
        
        // Top Products
        $sheet->setCellValue('A' . $row, 'PRODUK TERLARIS');
        $sheet->mergeCells('A' . $row . ':D' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'Nama Produk');
        $sheet->setCellValue('C' . $row, 'Total Terjual');
        $sheet->setCellValue('D' . $row, 'Total Pendapatan');
        $sheet->getStyle('A' . $row . ':D' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':D' . $row)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E0E0');
        $row++;
        
        $no = 1;
        foreach ($topProducts as $product) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $product['name']);
            $sheet->setCellValue('C' . $row, $product['total_quantity']);
            $sheet->setCellValue('D' . $row, $product['total_revenue']);
            $sheet->getStyle('D' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
            $no++;
        }
        $row++;
        
        // Sales by Status
        $sheet->setCellValue('A' . $row, 'PENJUALAN BERDASARKAN STATUS');
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Status');
        $sheet->setCellValue('B' . $row, 'Jumlah Pesanan');
        $sheet->setCellValue('C' . $row, 'Total Pendapatan');
        $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':C' . $row)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E0E0');
        $row++;
        
        foreach ($salesByStatus as $status) {
            $sheet->setCellValue('A' . $row, ucfirst($status['order_status']));
            $sheet->setCellValue('B' . $row, $status['count']);
            $sheet->setCellValue('C' . $row, $status['total']);
            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }
        $row++;
        
        // Daily Sales
        $sheet->setCellValue('A' . $row, 'PENJUALAN HARIAN');
        $sheet->mergeCells('A' . $row . ':C' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'Tanggal');
        $sheet->setCellValue('B' . $row, 'Jumlah Pesanan');
        $sheet->setCellValue('C' . $row, 'Total Pendapatan');
        $sheet->getStyle('A' . $row . ':C' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':C' . $row)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E0E0');
        $row++;
        
        foreach ($dailySales as $daily) {
            $sheet->setCellValue('A' . $row, date('d/m/Y', strtotime($daily['date'])));
            $sheet->setCellValue('B' . $row, $daily['orders']);
            $sheet->setCellValue('C' . $row, $daily['revenue']);
            $sheet->getStyle('C' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
        }
        $row++;
        
        // Detail Orders
        $sheet->setCellValue('A' . $row, 'DETAIL PESANAN');
        $sheet->mergeCells('A' . $row . ':F' . $row);
        $sheet->getStyle('A' . $row)->getFont()->setBold(true)->setSize(12);
        $row++;
        
        $sheet->setCellValue('A' . $row, 'No');
        $sheet->setCellValue('B' . $row, 'ID Pesanan');
        $sheet->setCellValue('C' . $row, 'Tanggal');
        $sheet->setCellValue('D' . $row, 'Customer');
        $sheet->setCellValue('E' . $row, 'Status');
        $sheet->setCellValue('F' . $row, 'Total');
        $sheet->getStyle('A' . $row . ':F' . $row)->getFont()->setBold(true);
        $sheet->getStyle('A' . $row . ':F' . $row)->getFill()
            ->setFillType(\PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID)
            ->getStartColor()->setRGB('E0E0E0');
        $row++;
        
        $no = 1;
        foreach ($orders as $order) {
            $sheet->setCellValue('A' . $row, $no);
            $sheet->setCellValue('B' . $row, $order['id']);
            $sheet->setCellValue('C' . $row, date('d/m/Y H:i', strtotime($order['order_date'])));
            $sheet->setCellValue('D' . $row, $order['customer_name']);
            $sheet->setCellValue('E' . $row, ucfirst($order['order_status']));
            $sheet->setCellValue('F' . $row, $order['amount']);
            $sheet->getStyle('F' . $row)->getNumberFormat()->setFormatCode('#,##0');
            $row++;
            $no++;
        }
        
        // Auto-size columns
        foreach (range('A', 'F') as $col) {
            $sheet->getColumnDimension($col)->setAutoSize(true);
        }
        
        // Create writer
        $writer = new \PhpOffice\PhpSpreadsheet\Writer\Xlsx($spreadsheet);
        
        // Save to temporary file
        $tempFile = sys_get_temp_dir() . '/' . uniqid('report_', true) . '.xlsx';
        $writer->save($tempFile);
        
        // Read file content
        $fileContent = file_get_contents($tempFile);
        unlink($tempFile);
        
        // Set response headers
        $response = service('response');
        $response->setHeader('Content-Type', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        $response->setHeader('Content-Disposition', 'attachment; filename="' . $filename . '"');
        $response->setHeader('Cache-Control', 'max-age=0');
        $response->setHeader('Pragma', 'public');
        
        return $response->setBody($fileContent);
    }
    
    private function exportPDF($startDate, $endDate, $salesData, $topProducts, $salesByStatus, $dailySales, $orders)
    {
        $filename = 'Laporan_Penjualan_' . $startDate . '_' . $endDate . '.pdf';
        
        // Create HTML content for PDF
        $html = '<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Laporan Penjualan</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { color: #333; border-bottom: 2px solid #f15a47; padding-bottom: 10px; }
        h2 { color: #555; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin: 20px 0; }
        th { background-color: #f15a47; color: white; padding: 10px; text-align: left; }
        td { padding: 8px; border: 1px solid #ddd; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        .summary { background-color: #f5f5f5; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .summary-item { margin: 5px 0; }
        .text-right { text-align: right; }
        .header-info { margin-bottom: 20px; color: #666; }
    </style>
</head>
<body>
    <h1>LAPORAN PENJUALAN</h1>
    <div class="header-info">
        <p><strong>Periode:</strong> ' . date('d/m/Y', strtotime($startDate)) . ' - ' . date('d/m/Y', strtotime($endDate)) . '</p>
        <p><strong>Tanggal Export:</strong> ' . date('d/m/Y H:i:s') . '</p>
    </div>
    
    <div class="summary">
        <h2>Ringkasan</h2>
        <div class="summary-item"><strong>Total Pendapatan:</strong> Rp ' . number_format($salesData['totalRevenue'], 0, ',', '.') . '</div>
        <div class="summary-item"><strong>Total Pesanan:</strong> ' . $salesData['totalOrders'] . '</div>
        <div class="summary-item"><strong>Pesanan Selesai:</strong> ' . $salesData['completedOrders'] . '</div>
        <div class="summary-item"><strong>Pesanan Pending:</strong> ' . $salesData['pendingOrders'] . '</div>
        <div class="summary-item"><strong>Pesanan Dibatalkan:</strong> ' . $salesData['cancelledOrders'] . '</div>
        <div class="summary-item"><strong>Rata-rata Pesanan:</strong> Rp ' . number_format($salesData['averageOrder'], 0, ',', '.') . '</div>
    </div>';
        
        // Top Products
        if (!empty($topProducts)) {
            $html .= '<h2>Produk Terlaris</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th class="text-right">Total Terjual</th>
                        <th class="text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>';
            $no = 1;
            foreach ($topProducts as $product) {
                $html .= '<tr>
                    <td>' . $no . '</td>
                    <td>' . htmlspecialchars($product['name']) . '</td>
                    <td class="text-right">' . number_format($product['total_quantity'], 0, ',', '.') . ' unit</td>
                    <td class="text-right">Rp ' . number_format($product['total_revenue'], 0, ',', '.') . '</td>
                </tr>';
                $no++;
            }
            $html .= '</tbody></table>';
        }
        
        // Sales by Status
        if (!empty($salesByStatus)) {
            $html .= '<h2>Penjualan Berdasarkan Status</h2>
            <table>
                <thead>
                    <tr>
                        <th>Status</th>
                        <th class="text-right">Jumlah Pesanan</th>
                        <th class="text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($salesByStatus as $status) {
                $html .= '<tr>
                    <td>' . ucfirst($status['order_status']) . '</td>
                    <td class="text-right">' . $status['count'] . '</td>
                    <td class="text-right">Rp ' . number_format($status['total'], 0, ',', '.') . '</td>
                </tr>';
            }
            $html .= '</tbody></table>';
        }
        
        // Daily Sales
        if (!empty($dailySales)) {
            $html .= '<h2>Penjualan Harian</h2>
            <table>
                <thead>
                    <tr>
                        <th>Tanggal</th>
                        <th class="text-right">Jumlah Pesanan</th>
                        <th class="text-right">Total Pendapatan</th>
                    </tr>
                </thead>
                <tbody>';
            foreach ($dailySales as $daily) {
                $html .= '<tr>
                    <td>' . date('d/m/Y', strtotime($daily['date'])) . '</td>
                    <td class="text-right">' . $daily['orders'] . '</td>
                    <td class="text-right">Rp ' . number_format($daily['revenue'], 0, ',', '.') . '</td>
                </tr>';
            }
            $html .= '</tbody></table>';
        }
        
        // Detail Orders
        if (!empty($orders)) {
            $html .= '<h2>Detail Pesanan</h2>
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>ID Pesanan</th>
                        <th>Tanggal</th>
                        <th>Customer</th>
                        <th>Status</th>
                        <th class="text-right">Total</th>
                    </tr>
                </thead>
                <tbody>';
            $no = 1;
            foreach ($orders as $order) {
                $html .= '<tr>
                    <td>' . $no . '</td>
                    <td>#' . $order['id'] . '</td>
                    <td>' . date('d/m/Y H:i', strtotime($order['order_date'])) . '</td>
                    <td>' . htmlspecialchars($order['customer_name']) . '</td>
                    <td>' . ucfirst($order['order_status']) . '</td>
                    <td class="text-right">Rp ' . number_format($order['amount'], 0, ',', '.') . '</td>
                </tr>';
                $no++;
            }
            $html .= '</tbody></table>';
        }
        
        $html .= '</body></html>';
        
        // For PDF, we'll use HTML output that can be printed as PDF
        // In a production environment, you would use a library like TCPDF or DomPDF
        // For now, we'll output HTML that browsers can print as PDF
        
        // Set response headers
        $response = service('response');
        $response->setHeader('Content-Type', 'text/html; charset=UTF-8');
        $response->setHeader('Content-Disposition', 'inline; filename="' . $filename . '"');
        
        // Add print script and styling for better PDF printing
        $html = str_replace('</body>', '
        <script>
            window.onload = function() {
                // Auto print when page loads
                setTimeout(function() {
                    window.print();
                }, 500);
            }
        </script>
        <style>
            @media print {
                body { margin: 0; padding: 15px; }
                h1, h2 { page-break-after: avoid; }
                table { page-break-inside: auto; }
                tr { page-break-inside: avoid; page-break-after: auto; }
                thead { display: table-header-group; }
                tfoot { display: table-footer-group; }
            }
        </style>
        </body>', $html);
        
        return $response->setBody($html);
    }
}

