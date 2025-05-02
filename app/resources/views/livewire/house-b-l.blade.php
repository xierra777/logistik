<div class="p-2">
    <div class="p-2 mb-5">
        <h2 class="text-2xl font-bold uppercase text-center">House Bill Lading </h2>
    </div>
    <div class="grid grid-cols-2 mb-4">
        <div class="block w-full flex">
            <p class="font-bold">Shipper : </p>
            <p>{{$shipment->shipper->name ?? ""}} / {{$shipmentId}}</p>
        </div>
        <div class="block w-full flex">
            <p class="font-bold">Consignee : </p>
            <p>{{$shipment->consignee->name ?? ""}} / {{$shipmentId}}</p>
        </div>
        <div class="block w-full flex">
            <p class="font-bold">Notify : </p>
            <p>{{$shipment->notify->name ?? ""}} / {{$shipmentId}}</p>
        </div>
        <div class="block w-full flex">
            <p class="font-bold">Job No : </p>
            <p>{{$shipment->shipment_no ?? ""}} / {{$shipmentId}}</p>
        </div>
    </div>
    <div class="block w-full col-span-2">
        <select name="" id="" class="block w-full rounded-md border-gray-300 shadow-sm focus:ring focus:ring-blue-200">
            <option value="">Original</option>
            <option value="">SeawayBill</option>
            <option value="">Telex Release</option>
            <option value="">ExpressBL</option>
        </select>
    </div>
    <div x-data="{ open: false, pdfSrc: '', loading: false }" x-cloak
        @open-pdf-preview.window="loading = true; open = true; pdfSrc = $event.detail.pdf; console.log('PDF Loaded:', pdfSrc); setTimeout(() => loading = false, 1000);">
        <div x-show="open" class="fixed inset-0 bg-gray-800 bg-opacity-75 flex justify-center items-center z-50">
            <div class="bg-white p-4 rounded-lg max-w-6xl w-full h-full flex flex-col">
                <div class="flex justify-end mb-2">
                    <button @click="open = false" class="bg-red-600 text-white px-4 py-2 rounded">Close</button>
                </div>
                <iframe
                    :src="pdfSrc"
                    class="w-full flex-grow border rounded"
                    frameborder="0">
                </iframe>
            </div>
        </div>
    </div>
    <div>
        <button wire:click="previewHBL" class="bg-yellow-500 text-white p-2 rounded mt-4">Preview Invoice</button>
        <button wire:click="generateHBL" class="bg-green-500 text-white p-2 rounded mt-4">Generate HBL</button>
    </div>
    <div class="flex justify-end p-3">
        <a wire:navigate href="/view-shipments/{{ $shipmentId }}" class="py-2 px-4 bg-cyan-500 text-white font-semibold rounded-md hover:shadow-lg
            transform transition duration-200 ease-in-out shadow:hover-cyan-200
            hover:bg-cyan-400 hover:scale-100 ">
            Back
        </a>
    </div>

</div>