@extends('layouts.admin')

@section('title', 'Paramètres de facturation - KLINKLIN Admin')

@section('page_title')
<div class="d-sm-flex align-items-center justify-content-between">
    <h1 class="h3 mb-0 text-gray-800">Paramètres de facturation</h1>
</div>
@endsection

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-lg-8">
            <!-- Information de l'entreprise -->
            <div class="card shadow mb-4">
                <div class="card-header py-3 d-flex justify-content-between align-items-center">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-file-invoice me-2"></i> Configuration des factures
                    </h6>
                </div>
                
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i> {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="fas fa-exclamation-circle me-2"></i> {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    @endif
                    
                    <form action="{{ route('admin.invoice-settings.update') }}" method="POST">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-left-primary">
                                    <div class="card-body">
                                        <h5 class="card-title">Informations de l'entreprise</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="company_name" class="form-label">Nom de l'entreprise <span class="text-danger">*</span></label>
                                                <input type="text" class="form-control @error('company_name') is-invalid @enderror" id="company_name" name="company_name" value="{{ old('company_name', $settings->company_name) }}" required>
                                                @error('company_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="phone" class="form-label">Téléphone</label>
                                                <input type="text" class="form-control @error('phone') is-invalid @enderror" id="phone" name="phone" value="{{ old('phone', $settings->phone) }}">
                                                @error('phone')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="email" class="form-label">Email</label>
                                                <input type="email" class="form-control @error('email') is-invalid @enderror" id="email" name="email" value="{{ old('email', $settings->email) }}">
                                                @error('email')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="website" class="form-label">Site web</label>
                                                <input type="text" class="form-control @error('website') is-invalid @enderror" id="website" name="website" value="{{ old('website', $settings->website) }}">
                                                @error('website')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-8">
                                                <label for="address" class="form-label">Adresse</label>
                                                <input type="text" class="form-control @error('address') is-invalid @enderror" id="address" name="address" value="{{ old('address', $settings->address) }}">
                                                @error('address')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-4">
                                                <label for="city" class="form-label">Ville</label>
                                                <input type="text" class="form-control @error('city') is-invalid @enderror" id="city" name="city" value="{{ old('city', $settings->city) }}">
                                                @error('city')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="registration_number" class="form-label">N° d'immatriculation (RCCM)</label>
                                                <input type="text" class="form-control @error('registration_number') is-invalid @enderror" id="registration_number" name="registration_number" value="{{ old('registration_number', $settings->registration_number) }}">
                                                @error('registration_number')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="tax_id" class="form-label">N° d'identification fiscale (NIU)</label>
                                                <input type="text" class="form-control @error('tax_id') is-invalid @enderror" id="tax_id" name="tax_id" value="{{ old('tax_id', $settings->tax_id) }}">
                                                @error('tax_id')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-left-success">
                                    <div class="card-body">
                                        <h5 class="card-title">Informations bancaires</h5>
                                        <div class="row g-3">
                                            <div class="col-md-6">
                                                <label for="bank_name" class="form-label">Nom de la banque</label>
                                                <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name', $settings->bank_name) }}">
                                                @error('bank_name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="bank_account" class="form-label">N° de compte</label>
                                                <input type="text" class="form-control @error('bank_account') is-invalid @enderror" id="bank_account" name="bank_account" value="{{ old('bank_account', $settings->bank_account) }}">
                                                @error('bank_account')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="bank_iban" class="form-label">IBAN</label>
                                                <input type="text" class="form-control @error('bank_iban') is-invalid @enderror" id="bank_iban" name="bank_iban" value="{{ old('bank_iban', $settings->bank_iban) }}">
                                                @error('bank_iban')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-md-6">
                                                <label for="bank_bic" class="form-label">BIC/SWIFT</label>
                                                <input type="text" class="form-control @error('bank_bic') is-invalid @enderror" id="bank_bic" name="bank_bic" value="{{ old('bank_bic', $settings->bank_bic) }}">
                                                @error('bank_bic')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                            
                                            <div class="col-12">
                                                <label for="payment_instructions" class="form-label">Instructions de paiement</label>
                                                <textarea class="form-control @error('payment_instructions') is-invalid @enderror" id="payment_instructions" name="payment_instructions" rows="3">{{ old('payment_instructions', $settings->payment_instructions) }}</textarea>
                                                @error('payment_instructions')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row mb-4">
                            <div class="col-12">
                                <div class="card border-left-info">
                                    <div class="card-body">
                                        <h5 class="card-title">Notes et mentions légales</h5>
                                        <div class="row g-3">
                                            <div class="col-12">
                                                <label for="invoice_notes" class="form-label">Mentions légales / Notes sur la facture</label>
                                                <textarea class="form-control @error('invoice_notes') is-invalid @enderror" id="invoice_notes" name="invoice_notes" rows="3">{{ old('invoice_notes', $settings->invoice_notes) }}</textarea>
                                                <small class="form-text text-muted">Par exemple : TVA non applicable en vertu des dispositions fiscales en vigueur.</small>
                                                @error('invoice_notes')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="d-flex justify-content-between">
                            <a href="{{ route('admin.configuration.index') }}" class="btn btn-outline-secondary">
                                <i class="fas fa-arrow-left me-1"></i> Retour
                            </a>
                            
                            <div>
                                <a href="{{ route('admin.invoice-settings.preview') }}" target="_blank" class="btn btn-info me-2">
                                    <i class="fas fa-eye me-1"></i> Aperçu facture
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-save me-1"></i> Enregistrer
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        
        <div class="col-lg-4">
            <div class="card shadow mb-4">
                <div class="card-header py-3">
                    <h6 class="m-0 font-weight-bold text-primary">
                        <i class="fas fa-info-circle me-2"></i> Aide
                    </h6>
                </div>
                <div class="card-body">
                    <p>Ces paramètres sont utilisés pour personnaliser l'apparence et le contenu des factures générées par le système.</p>
                    
                    <div class="alert alert-info">
                        <i class="fas fa-lightbulb me-2"></i> <strong>Conseil</strong>
                        <p class="mb-0">Pour des raisons de performance, il est recommandé de ne pas utiliser d'image de logo trop volumineuse dans les factures PDF.</p>
                    </div>
                    
                    <h6 class="font-weight-bold mt-4">Éléments personnalisables :</h6>
                    <ul class="list-group">
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-building me-3 text-primary"></i>
                            <div>
                                <strong>Informations de l'entreprise</strong>
                                <div class="small text-muted">Nom, adresse, coordonnées</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-money-check me-3 text-success"></i>
                            <div>
                                <strong>Informations bancaires</strong>
                                <div class="small text-muted">Banque, IBAN, BIC</div>
                            </div>
                        </li>
                        <li class="list-group-item d-flex align-items-center">
                            <i class="fas fa-file-contract me-3 text-info"></i>
                            <div>
                                <strong>Mentions légales</strong>
                                <div class="small text-muted">TVA, notes, immatriculation</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection 