<!-- Container (adjust max-w to control overall width) -->
<div class="max-w-7xl mx-auto p-4 bg-white">
    <!-- Heading Bar (Optional) -->
    <div class="bg-orange-500 p-3 mb-4">
        <h2 class="text-white text-lg font-semibold">Sale</h2>
    </div>

    <!-- Form -->
    <form class="space-y-4">
        <!-- Row 1 -->
        <div class="grid grid-cols-4 gap-4">
            <!-- Client -->
            <div>
                <label for="client" class="block text-sm font-medium text-gray-700">Client</label>
                <select id="client" name="client"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="PT-SALIM-INDOFOOD">PT.SALIM INDOFOOD - PT.SAMSUNG SDS</option>
                    <!-- More options... -->
                </select>
            </div>

            <!-- Amount / Qty -->
            <div>
                <label for="amountQty" class="block text-sm font-medium text-gray-700">Amount / Qty</label>
                <input type="number" id="amountQty" name="amountQty" placeholder="1100"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- D/T or G/F -->
            <div>
                <label for="dtgf" class="block text-sm font-medium text-gray-700">D/T or G/F</label>
                <input type="text" id="dtgf" name="dtgf" placeholder="G/F?"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- VAT / GST Amount -->
            <div>
                <label for="vatAmount" class="block text-sm font-medium text-gray-700">VAT / GST Amount</label>
                <input type="number" id="vatAmount" name="vatAmount" placeholder="2856000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>

        <!-- Row 2 -->
        <div class="grid grid-cols-4 gap-4">
            <!-- Remarks -->
            <div>
                <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                <input type="text" id="remarks" name="remarks" placeholder="Additional details..."
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Currency -->
            <div>
                <label for="currency" class="block text-sm font-medium text-gray-700">Currency</label>
                <select id="currency" name="currency"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="USD">USD</option>
                    <option value="IDR">IDR</option>
                    <!-- More options... -->
                </select>
            </div>

            <!-- FOB Amount -->
            <div>
                <label for="fobAmount" class="block text-sm font-medium text-gray-700">FOB Amount</label>
                <input type="number" id="fobAmount" name="fobAmount" placeholder="1100"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- VAT / GST Type -->
            <div>
                <label for="vatType" class="block text-sm font-medium text-gray-700">VAT / GST Type</label>
                <select id="vatType" name="vatType"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
                    <option value="gst">GST</option>
                    <option value="vat">VAT</option>
                    <!-- More options... -->
                </select>
            </div>
        </div>

        <!-- Row 3 -->
        <div class="grid grid-cols-4 gap-4">
            <!-- Ex Rate -->
            <div>
                <label for="exRate" class="block text-sm font-medium text-gray-700">Ex Rate</label>
                <input type="number" id="exRate" name="exRate" placeholder="6500"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Amount (IDR) -->
            <div>
                <label for="amountIdr" class="block text-sm font-medium text-gray-700">Amount (IDR)</label>
                <input type="number" id="amountIdr" name="amountIdr" placeholder="2866000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Balance Amount -->
            <div>
                <label for="balanceAmount" class="block text-sm font-medium text-gray-700">Balance Amount</label>
                <input type="number" id="balanceAmount" name="balanceAmount" placeholder="..."
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>

            <!-- Gross Profit -->
            <div>
                <label for="grossProfit" class="block text-sm font-medium text-gray-700">Gross Profit</label>
                <input type="number" id="grossProfit" name="grossProfit" placeholder="2866000"
                    class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-blue-500 focus:ring-blue-500">
            </div>
        </div>
    </form>
</div>