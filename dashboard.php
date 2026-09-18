<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>ICT Ticketing System</title>

    <link rel="stylesheet" href="public/css/app.css">

</head>

<body class="h-screen overflow-hidden bg-slate-100 text-slate-800">

<div class="flex h-screen overflow-hidden">


    <!-- ================= SIDEBAR ================= -->

    <aside class="hidden lg:flex h-screen w-64 shrink-0 flex-col bg-slate-900 text-white">

        <!-- Logo -->

        <div class="flex items-center gap-3 px-6 py-6 border-b border-slate-800">

            <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-600 text-xl">
                🎫
            </div>

            <div>

                <h1 class="font-bold text-lg">
                    ICT Helpdesk
                </h1>

                <p class="text-xs text-slate-400">
                    Ticketing System
                </p>

            </div>

        </div>


        <!-- Navigation -->

        <nav class="flex-1 px-4 py-6">

            <p class="px-3 mb-3 text-xs font-semibold tracking-wider text-slate-500">
                MAIN MENU
            </p>


            <!-- Dashboard -->

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg bg-blue-600 px-4 py-3 text-sm font-medium text-white">

                <span>📊</span>

                Dashboard

            </a>


            <!-- Tickets -->

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">

                <span>🎫</span>

                Tickets

                <span class="ml-auto rounded-full bg-slate-700 px-2 py-0.5 text-xs">
                    24
                </span>

            </a>


            <!-- Users -->

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">

                <span>👥</span>

                Users

            </a>


            <p class="px-3 mb-3 mt-8 text-xs font-semibold tracking-wider text-slate-500">
                MANAGEMENT
            </p>


            <!-- Reports -->

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">

                <span>📈</span>

                Reports

            </a>


            <!-- Settings -->

            <a href="#"
               class="mb-1 flex items-center gap-3 rounded-lg px-4 py-3 text-sm text-slate-300 hover:bg-slate-800 hover:text-white">

                <span>⚙️</span>

                Settings

            </a>

        </nav>


        <!-- User -->

        <div class="border-t border-slate-800 p-4">

            <div class="flex items-center gap-3">

                <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-600 font-bold">
                    JP
                </div>

                <div class="min-w-0">

                    <p class="truncate text-sm font-semibold">
                        John Paul
                    </p>

                    <p class="text-xs text-slate-400">
                        Administrator
                    </p>

                </div>

                <button class="ml-auto text-slate-400 hover:text-white">
                    ⋮
                </button>

            </div>

        </div>

    </aside>



    <!-- ================= MAIN ================= -->

    <main class="flex h-screen min-w-0 flex-1 flex-col overflow-hidden">


        <!-- ================= HEADER ================= -->

        <header class="flex h-20 shrink-0 items-center justify-between border-b border-slate-200 bg-white px-6 lg:px-8">

            <div>

                <h2 class="text-xl font-bold text-slate-900">
                    Dashboard
                </h2>

                <p class="mt-1 text-sm text-slate-500">
                    Good afternoon, John. Here's what's happening today.
                </p>

            </div>


            <div class="flex items-center gap-4">


                <!-- Notification -->

                <button class="relative flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-lg hover:bg-slate-50">

                    🔔

                    <span class="absolute right-1 top-1 flex h-4 min-w-4 items-center justify-center rounded-full bg-red-500 px-1 text-[9px] font-bold text-white">
                        4
                    </span>

                </button>


                <!-- Profile -->

                <div class="hidden items-center gap-3 sm:flex">

                    <div class="flex h-10 w-10 items-center justify-center rounded-full bg-blue-100 font-bold text-blue-700">
                        JP
                    </div>

                    <div>

                        <p class="text-sm font-semibold">
                            John Paul
                        </p>

                        <p class="text-xs text-slate-500">
                            Administrator
                        </p>

                    </div>

                </div>

            </div>

        </header>



        <!-- ================= CONTENT ================= -->

        <section class="min-h-0 flex-1 overflow-y-auto overflow-x-hidden p-6 lg:p-8">


            <!-- KPI CARDS -->

            <div class="grid gap-5 sm:grid-cols-2 xl:grid-cols-4">


                <!-- TOTAL -->

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-500">
                                Total Tickets
                            </p>

                            <h3 class="mt-2 text-3xl font-bold text-slate-900">
                                128
                            </h3>

                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-blue-100 text-xl">
                            🎫
                        </div>

                    </div>

                    <p class="mt-4 text-xs text-green-600">
                        ↑ 12% from last month
                    </p>

                </div>



                <!-- OPEN -->

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-500">
                                Open Tickets
                            </p>

                            <h3 class="mt-2 text-3xl font-bold text-slate-900">
                                24
                            </h3>

                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-amber-100 text-xl">
                            🔓
                        </div>

                    </div>

                    <p class="mt-4 text-xs text-amber-600">
                        Requires attention
                    </p>

                </div>



                <!-- PROGRESS -->

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-500">
                                In Progress
                            </p>

                            <h3 class="mt-2 text-3xl font-bold text-slate-900">
                                18
                            </h3>

                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-cyan-100 text-xl">
                            🔄
                        </div>

                    </div>

                    <p class="mt-4 text-xs text-cyan-600">
                        Currently being handled
                    </p>

                </div>



                <!-- CLOSED -->

                <div class="rounded-2xl border border-slate-200 bg-white p-5 shadow-sm">

                    <div class="flex items-start justify-between">

                        <div>

                            <p class="text-sm font-medium text-slate-500">
                                Closed Tickets
                            </p>

                            <h3 class="mt-2 text-3xl font-bold text-slate-900">
                                86
                            </h3>

                        </div>

                        <div class="flex h-11 w-11 items-center justify-center rounded-xl bg-green-100 text-xl">
                            ✓
                        </div>

                    </div>

                    <p class="mt-4 text-xs text-green-600">
                        67% resolution rate
                    </p>

                </div>

            </div>



            <!-- CHART + ACTIVITY -->

            <div class="mt-6 grid gap-6 xl:grid-cols-3">


                <!-- TICKET OVERVIEW -->

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm xl:col-span-2">

                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

                        <div>

                            <h3 class="font-bold text-slate-900">
                                Ticket Overview
                            </h3>

                            <p class="mt-1 text-xs text-slate-500">
                                Ticket activity for the current month
                            </p>

                        </div>


                        <select class="rounded-lg border border-slate-200 bg-white px-3 py-2 text-sm outline-none focus:border-blue-500">

                            <option>
                                This Month
                            </option>

                            <option>
                                Last Month
                            </option>

                            <option>
                                This Year
                            </option>

                        </select>

                    </div>


                    <!-- Simple Chart -->

                    <div class="flex h-72 items-end gap-4 px-6 py-8">

                        <div class="flex h-full flex-1 flex-col justify-end">

                            <div class="rounded-t-lg bg-blue-500" style="height:40%"></div>

                            <p class="mt-3 text-center text-xs text-slate-400">
                                Mon
                            </p>

                        </div>


                        <div class="flex h-full flex-1 flex-col justify-end">

                            <div class="rounded-t-lg bg-blue-500" style="height:65%"></div>

                            <p class="mt-3 text-center text-xs text-slate-400">
                                Tue
                            </p>

                        </div>


                        <div class="flex h-full flex-1 flex-col justify-end">

                            <div class="rounded-t-lg bg-blue-500" style="height:50%"></div>

                            <p class="mt-3 text-center text-xs text-slate-400">
                                Wed
                            </p>

                        </div>


                        <div class="flex h-full flex-1 flex-col justify-end">

                            <div class="rounded-t-lg bg-blue-500" style="height:80%"></div>

                            <p class="mt-3 text-center text-xs text-slate-400">
                                Thu
                            </p>

                        </div>


                        <div class="flex h-full flex-1 flex-col justify-end">

                            <div class="rounded-t-lg bg-blue-500" style="height:60%"></div>

                            <p class="mt-3 text-center text-xs text-slate-400">
                                Fri
                            </p>

                        </div>


                        <div class="flex h-full flex-1 flex-col justify-end">

                            <div class="rounded-t-lg bg-blue-500" style="height:90%"></div>

                            <p class="mt-3 text-center text-xs text-slate-400">
                                Sat
                            </p>

                        </div>


                        <div class="flex h-full flex-1 flex-col justify-end">

                            <div class="rounded-t-lg bg-blue-500" style="height:70%"></div>

                            <p class="mt-3 text-center text-xs text-slate-400">
                                Sun
                            </p>

                        </div>

                    </div>

                </div>



                <!-- RECENT ACTIVITY -->

                <div class="rounded-2xl border border-slate-200 bg-white shadow-sm">

                    <div class="flex items-center justify-between border-b border-slate-100 px-6 py-5">

                        <h3 class="font-bold text-slate-900">
                            Recent Activity
                        </h3>

                        <a href="#" class="text-xs font-semibold text-blue-600 hover:text-blue-700">
                            View All
                        </a>

                    </div>


                    <div>


                        <div class="flex gap-3 border-b border-slate-100 p-5">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-blue-100">
                                🎫
                            </div>

                            <div>

                                <p class="text-sm font-semibold">
                                    New ticket created
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Ticket #T-1028
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    5 minutes ago
                                </p>

                            </div>

                        </div>


                        <div class="flex gap-3 border-b border-slate-100 p-5">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-purple-100">
                                💬
                            </div>

                            <div>

                                <p class="text-sm font-semibold">
                                    New comment
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Ticket #T-1025
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    15 minutes ago
                                </p>

                            </div>

                        </div>


                        <div class="flex gap-3 p-5">

                            <div class="flex h-9 w-9 shrink-0 items-center justify-center rounded-lg bg-green-100">
                                ✓
                            </div>

                            <div>

                                <p class="text-sm font-semibold">
                                    Ticket resolved
                                </p>

                                <p class="mt-1 text-xs text-slate-500">
                                    Ticket #T-1019
                                </p>

                                <p class="mt-1 text-xs text-slate-400">
                                    30 minutes ago
                                </p>

                            </div>

                        </div>

                    </div>

                </div>

            </div>



            <!-- RECENT TICKETS -->

            <div class="mt-6 rounded-2xl border border-slate-200 bg-white shadow-sm">

                <div class="flex flex-col gap-3 border-b border-slate-100 px-6 py-5 sm:flex-row sm:items-center sm:justify-between">

                    <div>

                        <h3 class="font-bold text-slate-900">
                            Recent Tickets
                        </h3>

                        <p class="mt-1 text-xs text-slate-500">
                            Latest tickets submitted to ICT
                        </p>

                    </div>


                    <a href="#"
                       class="rounded-lg bg-blue-600 px-4 py-2 text-center text-sm font-semibold text-white hover:bg-blue-700">

                        View All Tickets

                    </a>

                </div>



                <div class="overflow-x-auto">

                    <table class="w-full text-left">

                        <thead class="bg-slate-50 text-xs uppercase text-slate-500">

                            <tr>

                                <th class="px-6 py-4">
                                    Ticket No.
                                </th>

                                <th class="px-6 py-4">
                                    Subject
                                </th>

                                <th class="px-6 py-4">
                                    Department
                                </th>

                                <th class="px-6 py-4">
                                    Priority
                                </th>

                                <th class="px-6 py-4">
                                    Status
                                </th>

                                <th class="px-6 py-4">
                                    Assigned To
                                </th>

                            </tr>

                        </thead>


                        <tbody class="divide-y divide-slate-100 text-sm">


                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4 font-semibold">
                                    T-1028
                                </td>

                                <td class="px-6 py-4">
                                    Computer not turning on
                                </td>

                                <td class="px-6 py-4">
                                    Accounting
                                </td>

                                <td class="px-6 py-4">

                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        High
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <span class="rounded-full bg-blue-100 px-3 py-1 text-xs font-semibold text-blue-700">
                                        Open
                                    </span>

                                </td>

                                <td class="px-6 py-4">
                                    ICT Staff
                                </td>

                            </tr>


                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4 font-semibold">
                                    T-1027
                                </td>

                                <td class="px-6 py-4">
                                    Printer problem
                                </td>

                                <td class="px-6 py-4">
                                    Human Resource
                                </td>

                                <td class="px-6 py-4">

                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        Medium
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <span class="rounded-full bg-yellow-100 px-3 py-1 text-xs font-semibold text-yellow-700">
                                        In Progress
                                    </span>

                                </td>

                                <td class="px-6 py-4">
                                    Mark
                                </td>

                            </tr>


                            <tr class="hover:bg-slate-50">

                                <td class="px-6 py-4 font-semibold">
                                    T-1026
                                </td>

                                <td class="px-6 py-4">
                                    Network connection issue
                                </td>

                                <td class="px-6 py-4">
                                    Radiology
                                </td>

                                <td class="px-6 py-4">

                                    <span class="rounded-full bg-red-100 px-3 py-1 text-xs font-semibold text-red-700">
                                        High
                                    </span>

                                </td>

                                <td class="px-6 py-4">

                                    <span class="rounded-full bg-green-100 px-3 py-1 text-xs font-semibold text-green-700">
                                        Closed
                                    </span>

                                </td>

                                <td class="px-6 py-4">
                                    John
                                </td>

                            </tr>


                        </tbody>

                    </table>

                </div>

            </div>


        </section>

    </main>

</div>

</body>

</html>